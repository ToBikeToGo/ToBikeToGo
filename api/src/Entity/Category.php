<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use App\Repository\CategoryRepository;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Constants\Groups as ConstantsGroups;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity()]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::CATEGORY_READ]],
)]
class Category
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::CATEGORY_READ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::CATEGORY_READ])]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[Groups([ConstantsGroups::CATEGORY_READ])]
    private ?TypeCategory $typeCategory = null;

    #[ORM\ManyToMany(targetEntity: Question::class, inversedBy: 'categories')]
    #[Groups([ConstantsGroups::CATEGORY_READ])]
    private Collection $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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

    public function getTypeCategory(): ?TypeCategory
    {
        return $this->typeCategory;
    }

    public function setTypeCategory(?TypeCategory $typeCategory): static
    {
        $this->typeCategory = $typeCategory;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        $this->questions->removeElement($question);

        return $this;
    }
}
