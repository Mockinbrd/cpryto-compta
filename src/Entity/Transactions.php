<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\TransactionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionsRepository::class)
 */
class Transactions
{
    use TimestampableTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * The coin name must match the coin id
     * @ORM\Column(type="string", length=100)
     */
    private ?string $coinId;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private ?string $currency;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $amount;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $tokenQuantityReceived;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?\DateTimeImmutable $transactionDate;

    /**
     * @ORM\ManyToOne(targetEntity=Portfolio::class, inversedBy="transactions")
     */
    private ?Portfolio $portfolio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getTokenQuantityReceived(): ?float
    {
        return $this->tokenQuantityReceived;
    }

    public function setTokenQuantityReceived(float $tokenQuantityReceived): self
    {
        $this->tokenQuantityReceived = $tokenQuantityReceived;

        return $this;
    }

    public function getTransactionDate(): ?\DateTimeImmutable
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(\DateTimeImmutable $transactionDate): self
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    public function getPortfolio(): ?Portfolio
    {
        return $this->portfolio;
    }

    public function setPortfolio(?Portfolio $portfolio): self
    {
        $this->portfolio = $portfolio;

        return $this;
    }

    public function getCoinId(): ?string
    {
        return $this->coinId;
    }

    public function setCoinId(string $coinId): self
    {
        $this->coinId = $coinId;

        return $this;
    }
}
