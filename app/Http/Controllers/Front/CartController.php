<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartItemRequest;
use App\Models\Product;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartRepositoryInterface $carts
    ) {
    }

    public function index(Request $request, string $locale)
    {
        $cart = $this->carts->forSession($request->session()->getId());
        $cart->load('items.product');
        $totals = $this->carts->totals($cart);

        if ($request->wantsJson()) {
            return response()->json(compact('cart', 'totals'));
        }

        return view('cart.index', compact('cart', 'totals'));
    }

    public function add(StoreCartItemRequest $request, string $locale, Product $product)
    {
        $cart = $this->carts->forSession($request->session()->getId());
        if ($product->stock < (int) $request->input('quantity', 1)) {
            return response()->json(['message' => 'Stock insuffisant'], 422);
        }
        $options = $request->input('selected_options', []);
        $qty = (int) $request->input('quantity', 1);
        $this->carts->addItem($cart, $product, $qty, $options);

        return response()->json(['message' => 'Produit ajouté']);
    }

    public function update(Request $request, string $locale)
    {
        $cart = $this->carts->forSession($request->session()->getId());
        $this->carts->updateItem($cart, (int) $request->input('item_id'), (int) $request->input('quantity'));
        $totals = $this->carts->totals($cart);

        return response()->json(['message' => 'Panier mis à jour', 'totals' => $totals]);
    }

    public function remove(Request $request, string $locale)
    {
        $cart = $this->carts->forSession($request->session()->getId());
        $this->carts->removeItem($cart, (int) $request->input('item_id'));
        $totals = $this->carts->totals($cart);

        return response()->json(['message' => 'Article supprimé', 'totals' => $totals]);
    }

    public function applyPromo(Request $request, string $locale)
    {
        $cart = $this->carts->forSession($request->session()->getId());
        $result = $this->carts->applyPromo($cart, $request->string('code')->toString());

        return response()->json($result);
    }
}
