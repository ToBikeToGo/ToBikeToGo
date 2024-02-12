<?php

namespace App\Entity;

use App\Entity\Auth\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use App\Repository\RequestRepository;
use App\Entity\Traits\TimestampableTrait;
use App\Constants\Groups as ConstantsGroups;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity()]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::REQUEST_READ]],
)]
class Request
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::REQUEST_READ])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::REQUEST_READ])]
    private ?\DateTime $requestDate = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::REQUEST_READ])]
    private ?bool $status = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[Groups([ConstantsGroups::REQUEST_READ])]
    private ?Franchise $franchise = null;

    #[ORM\OneToOne(inversedBy: 'request', cascade: ['persist', 'remove'])]
    #[Groups([ConstantsGroups::REQUEST_READ])]
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

    public function setStatus(bool $status): static
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
