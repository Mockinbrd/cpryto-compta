<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 */
class Purchase
{
    use TimestampableTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeImmutable $purchaseDate;

    /**
     * @ORM\Column(type="float")
     */
    private float $amountCrypto;

    /**
     * @ORM\Column(type="float")
     */
    private float $amountToEUR;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="purchases")
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Crypto::class, inversedBy="purchase")
     */
    private Crypto $crypto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchaseDate(): ?\DateTimeImmutable
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(\DateTimeImmutable $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getAmountCrypto(): ?float
    {
        return $this->amountCrypto;
    }

    public function setAmountCrypto(float $amountCrypto): self
    {
        $this->amountCrypto = $amountCrypto;

        return $this;
    }

    public function getAmountToEUR(): ?float
    {
        return $this->amountToEUR;
    }

    public function setAmountToEUR(float $amountToEUR): self
    {
        $this->amountToEUR = $amountToEUR;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCrypto(): ?Crypto
    {
        return $this->crypto;
    }

    public function setCrypto(?Crypto $crypto): self
    {
        $this->crypto = $crypto;

        return $this;
    }
}
