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

    public function index(Request $request)
    {
        $cart = $this->carts->forSession($request->session()->getId());
        $steps = ['shipping', 'billing', 'payment'];

        return response()->json(compact('cart', 'steps'));
    }

    public function store(StoreOrderRequest $request)
    {
        $user = Auth::user();
        $cart = $this->carts->forSession($request->session()->getId());
        $totals = $this->carts->totals($cart);

        $orderNumber = 'CMD-'.now()->format('Ym').'-'.Str::upper(Str::random(6));
        $order = $this->orders->create([
            'user_id' => $user->id,
            'order_number' => $orderNumber,
            'total' => $totals['total'],
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

        Mail::raw('Commande confirmée '.$order->order_number, function ($m) use ($user) {
            $m->to($user->email)->subject('Confirmation de commande');
        });

        // Envoyer la confirmation par WhatsApp
        $this->whatsapp->sendOrderConfirmation($order);

        return response()->json(['order' => $order, 'message' => 'Commande créée']);
    }
}
