<?php

namespace App\Entity\Shop;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Auth\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity()]
#[ApiResource(
    normalizationContext: ['groups' => ['product:read']],
    denormalizationContext: ['groups' => ['product:write']],
)]
class Product
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[Groups(['product:read', 'product:write'])]
    #[ORM\Column(length: 255)]
    private string $name = '';

    #[Groups(['product:read', 'product:read:logged', 'product:write'])]
    #[ORM\Column]
    private int $price = 0;

    #[Groups(['product:read', 'admin:manage'])]
    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[Groups(['product:read:authorized'])]
    #[ORM\Column(length: 255)]
    private string $documentationUrl = '';

    #[Groups(['product:read:admin'])]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'products')]
    private Collection $buyers;

    public function __construct()
    {
        $this->buyers = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getBuyers(): Collection
    {
        return $this->buyers;
    }

    public function addBuyer(User $buyer): void
    {
        if (!$this->buyers->contains($buyer)) {
            $this->buyers->add($buyer);
        }
    }

    public function removeBuyer(User $buyer): void
    {
        $this->buyers->removeElement($buyer);
    }

    public function getDocumentationUrl(): string
    {
        return $this->documentationUrl;
    }

    public function setDocumentationUrl(string $documentationUrl): void
    {
        $this->documentationUrl = $documentationUrl;
    }
}
