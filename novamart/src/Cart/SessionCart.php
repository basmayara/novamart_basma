<?php

namespace App\Cart;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionCart implements CartInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository,  // ← ajoute ça
    ) {}

    private function getSession(): \Symfony\Component\HttpFoundation\Session\SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function add(CartItem $item, Cart $cart): Cart
    {
        $key      = 'cart_' . $cart->getId();
        $cartData = $this->getSession()->get($key, []);

        foreach ($cartData as &$existing) {
            if ($existing['product_id'] === $item->getProduct()->getId()) {
                $existing['quantity'] += $item->getQuantity();
                $this->getSession()->set($key, $cartData);
                return $cart;
            }
        }

        $cartData[] = [
            'product_id' => $item->getProduct()->getId(),
            'name'       => $item->getProduct()->getName(),
            'price'      => $item->getProduct()->getPrice(),
            'quantity'   => $item->getQuantity(),
        ];

        $this->getSession()->set($key, $cartData);
        return $cart;
    }

    public function remove(CartItem $item, Cart $cart): Cart
    {
        $key      = 'cart_' . $cart->getId();
        $cartData = $this->getSession()->get($key, []);

        $cartData = array_values(array_filter(
            $cartData,
            fn(array $i) => $i['product_id'] !== $item->getProduct()->getId()
        ));

        $this->getSession()->set($key, $cartData);
        return $cart;
    }

    public function getCart(string $identifier): Cart
    {
        $cartData = $this->getSession()->get('cart_' . $identifier, []);

        $cart = new Cart();
        $cart->setId($identifier);

        foreach ($cartData as $data) {
            // Récupère le vrai Product depuis la base ← c'est le fix
            $product = $this->productRepository->find($data['product_id']);

            if (!$product) {
                continue;
            }

            $item = new CartItem();
            $item->setProduct($product);
            $item->setPrice($data['price']);
            $item->setQuantity($data['quantity']);
            $cart->addCartItem($item);
        }

        return $cart;
    }

    public function clearCart(string $identifier): void
    {
        $this->getSession()->remove('cart_' . $identifier);
    }
}