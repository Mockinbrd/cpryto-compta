<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * The coin name must match the coin id
     * @ORM\Column(type="string", length=255)
     */
    private ?string $coinName;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeImmutable $purchaseDate;

    /**
     * @ORM\Column(type="float")
     */
    private float $amountToCrypto;

    /**
     * @ORM\Column(type="float")
     */
    private float $amountToEUR;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="purchases")
     */
    private UserInterface $user;

    /**
     * @ORM\ManyToOne(targetEntity=Bag::class, inversedBy="purchase")
     */
    private ?Bag $bag;


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

    public function getAmountToCrypto(): ?float
    {
        return $this->amountToCrypto;
    }

    public function setAmountToCrypto(float $amountToCrypto): self
    {
        $this->amountToCrypto = $amountToCrypto;

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

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBag(): ?Bag
    {
        return $this->bag;
    }

    public function setBag(?Bag $bag): self
    {
        $this->bag = $bag;

        return $this;
    }

    public function getCoinName(): ?string
    {
        return $this->coinName;
    }

    public function setCoinName(string $coinName): self
    {
        $this->coinName = $coinName;

        return $this;
    }
}
