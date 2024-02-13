<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use App\Constants\Groups as ConstantsGroups;
use App\Controller\RemoveVacationAction;
use App\Controller\ValidatedVacationAction;
use App\Entity\Auth\User;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\VacationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VacationRepository::class)]
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
            normalizationContext: ["groups" => [ConstantsGroups::SHOP_VACATIONS_READ]],
        ),
        new Delete(
            uriTemplate: '/vacations/{id}/remove',
            controller: RemoveVacationAction::class,
        ),
        new Patch(
            uriTemplate: '/vacations/{id}/validated',
            controller: ValidatedVacationAction::class,
        ),
        new Patch(denormalizationContext: ['groups' => [ConstantsGroups::VACATION_WRITE]]),

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
    #[Groups([ConstantsGroups::SHOP_VACATIONS_READ, ConstantsGroups::VACATION_WRITE, ConstantsGroups::SHOP_MEMBERS_READ, ConstantsGroups::USER_READ])]
    private ?\DateTime $startDate = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::SHOP_VACATIONS_READ, ConstantsGroups::VACATION_WRITE, ConstantsGroups::SHOP_MEMBERS_READ, ConstantsGroups::USER_READ])]
    private ?\DateTime $endDate = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups([ConstantsGroups::SHOP_VACATIONS_READ, ConstantsGroups::VACATION_WRITE])]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'vacations')]
    private ?Shop $shop = null;

    #[ORM\ManyToOne(inversedBy: 'vacations')]
    #[Groups([ConstantsGroups::SHOP_VACATIONS_READ])]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([ConstantsGroups::SHOP_VACATIONS_READ, ConstantsGroups::VACATION_WRITE, ConstantsGroups::SHOP_MEMBERS_READ, ConstantsGroups::USER_READ])]
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
