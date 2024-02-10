<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use App\Constants\Groups as ConstantsGroups;
use App\Entity\Auth\User;
use ApiPlatform\Metadata\Link;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\TimestampableTrait;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity()]
#[ApiResource]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/shops/{shopId}/vacations",
            uriVariables: [
                "shopId" => new Link(
                    fromClass: Shop::class,
                    fromProperty: "vacations"
                )
            ],
        )
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'updatedAt'], arguments: ['orderParameterName' => 'order'])]
class Vacation
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['shop:vacations:read', ConstantsGroups::USER_READ])]
    private ?\DateTime $startDate = null;

    #[ORM\Column]
    #[Groups(['shop:vacations:read', ConstantsGroups::USER_READ])]
    private ?\DateTime $endDate = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['shop:vacations:read'])]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'vacations')]
    private ?Shop $shop = null;

    #[ORM\ManyToOne(inversedBy: 'vacations')]
    #[Groups(['shop:vacations:read'])]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['shop:vacations:read', ConstantsGroups::USER_READ])]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): static
    {
        $this->shop = $shop;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
