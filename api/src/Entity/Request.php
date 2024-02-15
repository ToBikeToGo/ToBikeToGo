<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Odm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Odm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Auth\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use App\Repository\RequestRepository;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Constants\Groups as ConstantsGroups;

#[ORM\Entity()]
#[GetCollection(
    normalizationContext: ['groups' => ['request:validate']]
), Patch(), Post(), Get(
    normalizationContext: ['groups' => [ConstantsGroups::REQUEST_READ]],
)]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::REQUEST_READ]],
    order: ['id' => 'DESC'],
)]
class Request
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['request:validate'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::REQUEST_READ])]
    private ?\DateTime $requestDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups([ConstantsGroups::REQUEST_READ, 'request:validate'])]
    private ?bool $status = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[Groups(['request:validate'])]
    private ?Franchise $franchise = null;

    #[ORM\OneToOne(inversedBy: 'request', cascade: ['persist', 'remove'])]
    #[Groups([ConstantsGroups::REQUEST_READ, 'request:validate'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestDate(): ?\DateTime
    {
        return $this->requestDate;
    }

    public function setRequestDate(\DateTime $requestDate): static
    {
        $this->requestDate = $requestDate;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getFranchise(): ?Franchise
    {
        return $this->franchise;
    }

    public function setFranchise(?Franchise $franchise): static
    {
        $this->franchise = $franchise;

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
}
