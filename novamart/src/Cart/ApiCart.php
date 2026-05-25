<?php

namespace App\Cart;

use App\Entity\Cart;
use App\Entity\CartItem;

class ApiCart implements CartInterface
{
    public function add(CartItem $item, Cart $cart): Cart
    {
        dd(['action' => 'ApiCart::add', 'product' => $item->getProduct()->getName()]);
    }

    public function remove(CartItem $item, Cart $cart): Cart
    {
        dd(['action' => 'ApiCart::remove', 'product' => $item->getProduct()->getName()]);
    }

    public function getCart(string $identifier): Cart
    {
        dd(['action' => 'ApiCart::getCart', 'identifier' => $identifier]);
    }

    public function clearCart(string $identifier): void
    {
        dd(['action' => 'ApiCart::clearCart', 'identifier' => $identifier]);
    }
}