<?php

namespace App\Repositories\Contracts;

use App\Models\Cart;
use App\Models\Product;

interface CartRepositoryInterface
{
    public function forSession(string $sessionId): Cart;

    public function addItem(Cart $cart, Product $product, int $quantity = 1, array $options = [], ?float $price = null): void;

    public function updateItem(Cart $cart, int $itemId, int $quantity): void;

    public function removeItem(Cart $cart, int $itemId): void;

    public function totals(Cart $cart): array;

    public function applyPromo(Cart $cart, ?string $code): array;
}
