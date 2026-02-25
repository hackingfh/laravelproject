<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartRepositoryInterface $carts,
        private readonly OrderRepositoryInterface $orders,
        private readonly WhatsAppService $whatsapp
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request, string $locale)
    {
        $cart = $this->carts->forSession($request->session()->getId());
        $steps = ['shipping', 'billing', 'payment'];

        return view('checkout.index', compact('cart', 'steps'));
    }

    public function store(StoreOrderRequest $request)
    {
        $user = Auth::user();
        $cart = $this->carts->forSession($request->session()->getId());

        if ($cart->items->isEmpty()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Votre panier est vide'], 422);
            }
            return redirect()->route('home', ['locale' => app()->getLocale()])->with('error', 'Votre panier est vide.');
        }

        $order = \DB::transaction(function () use ($user, $cart, $request) {
            // Combine country code and phone number
            $countryCode = preg_replace('/[^0-9]/', '', $request->input('country_code'));
            $phoneNumber = preg_replace('/[^0-9]/', '', $request->input('phone_number'));
            $fullPhone = '+' . $countryCode . $phoneNumber;

            // Update user phone for future orders and notifications
            $user->update(['phone' => $fullPhone]);

            $orderNumber = 'CMD-' . now()->format('Ym') . '-' . Str::upper(Str::random(6));
            $order = $this->orders->create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'total' => $this->carts->totals($cart)['total'],
                'status' => 'pending',
                'shipping_address' => $request->input('shipping_address'),
                'payment_method' => $request->input('payment_method', 'card'),
                'payment_status' => 'pending',
                'tracking_number' => null,
                'notes' => $request->input('notes'),
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->price_at_addition,
                    'product_snapshot' => [
                        'name' => $item->product->name,
                        'reference' => $item->product->reference,
                        'sku' => $item->product->sku,
                        'price' => $item->product->price,
                    ],
                    'selected_options' => $item->selected_options,
                ]);
            }

            // Clear cart as part of the transaction
            $this->carts->clear($cart);

            return $order;
        });

        // Notifications after successful transaction
        try {
            Mail::raw('Commande confirmée ' . $order->order_number, function ($m) use ($user) {
                $m->to($user->email)->subject('Confirmation de commande');
            });
            $this->whatsapp->sendOrderConfirmation($order);
        } catch (\Exception $e) {
            \Log::error('Order notification failed: ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json(['order' => $order, 'message' => 'Commande créée']);
        }

        return redirect()->route('orders.show', ['locale' => app()->getLocale(), 'id' => $order->id])->with('success', 'Commande créée avec succès !');
    }
}
