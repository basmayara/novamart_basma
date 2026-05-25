<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private Cart $cart;

    // Votre Product a getPrice() et getName() — on s'adapte
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Product $product;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    public function getId(): ?int { return $this->id; }

    public function getCart(): Cart { return $this->cart; }
    public function setCart(Cart $cart): self { $this->cart = $cart; return $this; }

    public function getProduct(): Product { return $this->product; }
    public function setProduct(Product $product): self { $this->product = $product; return $this; }

    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): self { $this->quantity = $quantity; return $this; }
}