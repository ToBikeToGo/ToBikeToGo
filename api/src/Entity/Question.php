<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use App\Repository\QuestionRepository;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Constants\Groups as ConstantsGroups;

#[ORM\Entity()]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::QUESTION_READ]],
)]
class Question
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::QUESTION_READ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::QUESTION_READ, ConstantsGroups::PROPOSITION_READ, ConstantsGroups::CATEGORY_READ])]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([ConstantsGroups::QUESTION_READ, ConstantsGroups::CATEGORY_READ])]
    private ?TypeQuestion $typeQuestion = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Proposition::class)]
    #[Groups([ConstantsGroups::QUESTION_READ, ConstantsGroups::CATEGORY_READ])]
    private Collection $propositions;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'questions')]
    #[Groups([ConstantsGroups::QUESTION_READ])]
    private Collection $categories;

    public function __construct()
    {
        $this->propositions = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getTypeQuestion(): ?TypeQuestion
    {
        return $this->typeQuestion;
    }

    public function setTypeQuestion(?TypeQuestion $typeQuestion): static
    {
        $this->typeQuestion = $typeQuestion;

        return $this;
    }

    /**
     * @return Collection<int, Proposition>
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function addProposition(Proposition $proposition): static
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions->add($proposition);
            $proposition->setQuestion($this);
        }

        return $this;
    }

    public function removeProposition(Proposition $proposition): static
    {
        if ($this->propositions->removeElement($proposition) && $proposition->getQuestion() === $this) {
            // set the owning side to null (unless already changed)
            $proposition->setQuestion(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addQuestion($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeQuestion($this);
        }

        return $this;
    }
}
