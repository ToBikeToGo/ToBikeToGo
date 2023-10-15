<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use App\Repository\RequestRepository;
use App\Entity\Traits\TimestampableTrait;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
#[ApiResource]
class Request
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $requestDate = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    private ?Franchise $franchise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestDate(): ?\DateTimeImmutable
    {
        return $this->requestDate;
    }

    public function setRequestDate(\DateTimeImmutable $requestDate): static
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
}
