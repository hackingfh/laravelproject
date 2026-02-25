<?php

namespace App\Services;

use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Session\SessionManager;

class CartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private SessionManager $session
    ) {
    }

    public function count(): int
    {
        $sessionId = $this->session->getId();
        $cart = $this->cartRepository->forSession($sessionId);

        return $cart->items->sum('quantity');
    }

    public function getCart()
    {
        $sessionId = $this->session->getId();
        return $this->cartRepository->forSession($sessionId);
    }

    public function addItem($productId, $quantity = 1, array $options = [], ?float $price = null): void
    {
        $product = \App\Models\Product::findOrFail($productId);
        $cart = $this->getCart();
        $this->cartRepository->addItem($cart, $product, $quantity, $options, $price);
    }

    public function updateItem($itemId, $quantity): void
    {
        $cart = $this->getCart();
        $this->cartRepository->updateItem($cart, $itemId, $quantity);
    }

    public function removeItem($itemId): void
    {
        $cart = $this->getCart();
        $this->cartRepository->removeItem($cart, $itemId);
    }

    public function clear(): void
    {
        $cart = $this->getCart();
        $this->cartRepository->clear($cart);
    }

    public function totals(): array
    {
        $cart = $this->getCart();
        return $this->cartRepository->totals($cart);
    }
}
