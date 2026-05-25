<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Cart
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['persist', 'remove'])]
    private Collection $cartItems;

    public function __construct()
    {
        $this->id        = uniqid('cart_', true);
        $this->createdAt = new \DateTime();
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): string { return $this->id; }

    public function setId(string $id): self { $this->id = $id; return $this; }

    public function getCartItems(): Collection { return $this->cartItems; }

    public function addCartItem(CartItem $item): self
    {
        if (!$this->cartItems->contains($item)) {
            $this->cartItems->add($item);
            $item->setCart($this);
        }
        return $this;
    }

    public function removeCartItem(CartItem $item): self
    {
        $this->cartItems->removeElement($item);
        return $this;
    }

    public function total(): float
    {
        return array_sum(
            $this->cartItems
                ->map(fn(CartItem $i) => $i->getPrice() * $i->getQuantity())
                ->toArray()
        );
    }
}