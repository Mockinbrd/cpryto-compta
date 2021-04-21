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
 * @ApiResource(
 *      security="is_granted('ROLE_USER')",
 *      collectionOperations={
 *          "get",
 *          "post" = { "security" = "is_granted('ROLE_USER')" }
 *      },
 *     itemOperations={
 *          "get"={
 *                  "normalization_context"={
 *                      "groups"={
 *                          "transaction:read", "transaction:item:get"
 *                      }
 *                  },
 *          },
 *          "put" = {
 *              "security" = "is_granted('EDIT_TRANSACTION', previous_object)",
 *              "security_message" = "Only the creator can edit this transaction"
 *          },
 *          "delete" = {"security" = "is_granted('ROLE_ADMIN')"}
 *      },
 *     attributes={
 *           "pagination_items_per_page"=25,
 *           "formats"={"json", "html"}
 *     }
 * )
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
     * @Groups({"transaction:read", "transaction:write"})
     * @Assert\NotBlank()
     */
    private string $coinId = '';

    /**
     * @ORM\Column(type="string", length=3)
     * @Groups({"transaction:read", "transaction:write"})
     * @Assert\NotBlank()
     */
    private string $currency = '';

    /**
     * @ORM\Column(type="float")
     * @Groups({"transaction:read", "transaction:write"})
     * @Assert\NotBlank()
     */
    private float $amount = 0.0;

    /**
     * @ORM\Column(type="float")
     * @Groups({"transaction:read", "transaction:write"})
     * @Assert\NotBlank()
     */
    private float $tokenQuantityReceived = 0.0;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"transaction:read", "transaction:write"})
     * @Assert\NotBlank()
     */
    private ?\DateTimeImmutable $transactionDate = null;

    /**
     * @ORM\ManyToOne(targetEntity=Portfolio::class, inversedBy="transactions")
     * @Groups({"transaction:read"})
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

    public function setPortfolio(Portfolio $portfolio): self
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
