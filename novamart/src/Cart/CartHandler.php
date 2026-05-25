<?php

namespace App\Cart;

use App\Entity\Cart;
use App\Entity\CartItem;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CartHandler
{
    public function __construct(
        #[Autowire(service: 'App\Cart\SessionCart')]
        private CartInterface $cartStrategy,
    ) {}

    public function addItem(CartItem $item, Cart $cart): Cart
    {
        return $this->cartStrategy->add($item, $cart);
    }

    public function removeItem(CartItem $item, Cart $cart): Cart
    {
        return $this->cartStrategy->remove($item, $cart);
    }

    public function getCart(string $identifier): Cart
    {
        return $this->cartStrategy->getCart($identifier);
    }

    public function clear(Cart $cart): void
    {
        $this->cartStrategy->clearCart($cart->getId());
    }
}