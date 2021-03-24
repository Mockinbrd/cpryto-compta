<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\TransactionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TransactionsRepository::class)
 * @ApiResource()
 */
class Transactions
{
    use TimestampableTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("portfolio:read")
     */
    private ?int $id;

    /**
     * The coin name must match the coin id
     * @ORM\Column(type="string", length=100)
     * @Groups("portfolio:read")
     * @Assert\NotBlank()
     */
    private string $coinId = '';

    /**
     * @ORM\Column(type="string", length=3)
     * @Groups("portfolio:read")
     * @Assert\NotBlank()
     */
    private string $currency = '';

    /**
     * @ORM\Column(type="float")
     * @Groups("portfolio:read")
     * @Assert\NotBlank()
     */
    private float $amount = 0.0;

    /**
     * @ORM\Column(type="float")
     * @Groups("portfolio:read")
     * @Assert\NotBlank()
     */
    private float $tokenQuantityReceived = 0.0;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups("portfolio:read")
     * @Assert\NotNull()
     */
    private ?\DateTimeImmutable $transactionDate = null;

    /**
     * @ORM\ManyToOne(targetEntity=Portfolio::class, inversedBy="transactions")
     */
    private ?Portfolio $portfolio = null;

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
