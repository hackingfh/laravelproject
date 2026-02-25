<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static int count()
 * @method static \App\Models\Cart getCart()
 * @method static void addItem(int $productId, int $quantity = 1, array $options = [], ?float $price = null)
 * @method static void updateItem(int $itemId, int $quantity)
 * @method static void removeItem(int $itemId)
 * @method static array totals()
 * @see \App\Services\CartService
 */
class Cart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}
