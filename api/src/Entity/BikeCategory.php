<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use App\Constants\Groups as ConstantsGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(normalizationContext: ['groups' => [ConstantsGroups::BIKE_CATEGORY_READ]])]
class BikeCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::BIKE_CATEGORY_READ,ConstantsGroups::SHOP_READ ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::BIKE_CATEGORY_READ, ConstantsGroups::BIKE_READ, 'bike_category:categories:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::BIKE_CATEGORY_READ, ConstantsGroups::BIKE_READ])]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Bike::class)]
    #[Groups([ConstantsGroups::BIKE_CATEGORY_READ])]
    private Collection $bikes;

    public function __construct()
    {
        $this->bikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Bike>
     */
    public function getBikes(): Collection
    {
        return $this->bikes;
    }

    public function addBike(Bike $bike): static
    {
        if (!$this->bikes->contains($bike)) {
            $this->bikes->add($bike);
            $bike->setCategory($this);
        }

        return $this;
    }

    public function removeBike(Bike $bike): static
    {
        if ($this->bikes->removeElement($bike) && $bike->getCategory() === $this){
                $bike->setCategory(null);
        }

        return $this;
    }
}
