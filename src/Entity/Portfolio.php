<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\PortfolioRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=PortfolioRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post" = { "security" = "is_granted('ROLE_USER')" }
 *      },
 *     itemOperations={
 *          "get"={
 *                  "normalization_context"={
 *                      "groups"={
 *                          "portfolio:read", "portfolio:item:get"
 *                      }
 *                  },
 *          },
 *          "put" = {
 *              "security" = "is_granted('EDIT', previous_object)",
 *              "security_message" = "Only the creator can edit this portfolio"
 *          },
 *          "delete" = {"security" = "is_granted('ROLE_ADMIN')"}
*      },
 *     attributes={
 *           "pagination_items_per_page"=10,
*            "formats"={"jsonld", "json", "html", "csv"={"text/csv"}}
 *     },
 *     normalizationContext={"groups"={"portfolio:read", "portfolio:item:get"}},
 *     denormalizationContext={"groups"={"portfolio:write"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "name": "partial",
 *     "user": "exact",
 *     "user.email": "partial"
 * })
 * @ApiFilter(PropertyFilter::class)
 */
class Portfolio
{
    use TimestampableTrait;
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     * @Groups({"portfolio:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"portfolio:read", "portfolio:write", "user:read", "user:write"})
     * @Assert\NotBlank()
     */
    private string $name = '';

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="portfolios")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"portfolio:read", "portfolio:write"})
     * @Assert\Valid()
     */
    private ?User $user;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="portfolio", cascade={"persist", "remove"})
     * @Groups({"portfolio:read", "user:read"})
     */
    private iterable $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection|Transactions[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transactions $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setPortfolio($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getPortfolio() === $this) {
                $transaction->setPortfolio(null);
            }
        }

        return $this;
    }

    /**
     * Returns the time passed since the date
     * @Groups({"portfolio:read"})
     */
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }
}
