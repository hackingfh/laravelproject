<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Repositories\Contracts\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function forSession(string $sessionId): Cart
    {
        return Cart::firstOrCreate(['session_id' => $sessionId], [
            'total' => 0,
        ]);
    }

    public function addItem(Cart $cart, Product $product, int $quantity = 1, array $options = [], ?float $price = null): void
    {
        $priceAtAddition = $price ?? (float) $product->price;
        $item = new CartItem([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'selected_options' => $options,
            'price_at_addition' => $priceAtAddition,
        ]);
        $cart->items()->save($item);
    }

    public function updateItem(Cart $cart, int $itemId, int $quantity): void
    {
        $cart->items()->where('id', $itemId)->update(['quantity' => $quantity]);
    }

    public function removeItem(Cart $cart, int $itemId): void
    {
        $cart->items()->where('id', $itemId)->delete();
    }

    public function totals(Cart $cart): array
    {
        $subtotal = $cart->items->sum(fn ($i) => $i->quantity * (float) $i->price_at_addition);
        $shipping = $subtotal > 1000 ? 0 : 15;
        $tax = round($subtotal * 0.077, 2);
        $total = round($subtotal + $shipping + $tax, 2);
        $cart->update(['total' => $total]);

        return compact('subtotal', 'shipping', 'tax', 'total');
    }

    public function applyPromo(Cart $cart, ?string $code): array
    {
        $totals = $this->totals($cart);
        $discount = 0;
        if ($code === 'PROMO10') {
            $discount = round($totals['subtotal'] * 0.10, 2);
        }
        $totals['total'] = max(0, $totals['total'] - $discount);

        return $totals + ['discount' => $discount, 'code' => $code];
    }
}
