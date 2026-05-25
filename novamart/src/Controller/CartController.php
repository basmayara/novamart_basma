<?php

namespace App\Controller;

use App\Cart\CartHandler;
use App\Entity\CartItem;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(private CartHandler $cartHandler) {}

    // Votre route existante — on y ajoute l'affichage du panier
    #[Route('/cart', name: 'app_cart')]
    public function index(Request $request): Response
    {
        $cartId = $request->getSession()->get('cart_id', uniqid('cart_', true));
        $request->getSession()->set('cart_id', $cartId);

        $cart = $this->cartHandler->getCart($cartId);

        return $this->render('cart/cart.html.twig', [
            'cart'  => $cart,
            'total' => $cart->total(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['GET'])]
    public function add(int $id, ProductRepository $productRepository, Request $request): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit introuvable.');
        }

        $cartId = $request->getSession()->get('cart_id', uniqid('cart_', true));
        $request->getSession()->set('cart_id', $cartId);

        $cart = $this->cartHandler->getCart($cartId);

        $item = new CartItem();
        $item->setProduct($product);
        // On utilise getPrice() de VOTRE Product
        $item->setPrice($product->getPrice());
        $item->setQuantity(1);

        $this->cartHandler->addItem($item, $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(int $id, ProductRepository $productRepository, Request $request): Response
    {
        $product = $productRepository->find($id);
        $cartId  = $request->getSession()->get('cart_id');

        if ($product && $cartId) {
            $cart = $this->cartHandler->getCart($cartId);
            $item = new CartItem();
            $item->setProduct($product);
            $item->setPrice($product->getPrice());
            $item->setQuantity(0);
            $this->cartHandler->removeItem($item, $cart);
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/clear', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(Request $request): Response
    {
        $cartId = $request->getSession()->get('cart_id');

        if ($cartId) {
            $cart = $this->cartHandler->getCart($cartId);
            $this->cartHandler->clear($cart);
        }

        return $this->redirectToRoute('app_cart');
    }
}