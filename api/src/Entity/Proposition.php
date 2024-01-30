<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\PropositionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Constants\Groups as ConstantsGroups;


#[ORM\Entity()]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::PROPOSITION_READ]],
)]
class Proposition
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::PROPOSITION_READ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::PROPOSITION_READ, ConstantsGroups::BIKE_READ])]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'propositions')]
    #[Groups([ConstantsGroups::PROPOSITION_READ, ConstantsGroups::BIKE_READ])]
    private ?Question $question = null;

    #[ORM\ManyToMany(targetEntity: Bike::class, mappedBy: 'propositions')]
    #[Groups([ConstantsGroups::PROPOSITION_READ])]
    private Collection $bikes;

    public function __construct()
    {
        $this->bikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

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
            $bike->addProposition($this);
        }

        return $this;
    }

    public function removeBike(Bike $bike): static
    {
        if ($this->bikes->removeElement($bike)) {
            $bike->removeProposition($this);
        }

        return $this;
    }
}
