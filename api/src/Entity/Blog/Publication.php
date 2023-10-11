<?php

namespace App\Entity\Blog;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Auth\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['post:read']],
    operations: [
        new GetCollection(),

        new Post(denormalizationContext: ['groups' => ['post:update', 'post:create']]),
        new Get(normalizationContext: ['groups' => ['post:read', 'post:read:full']]),
        new Patch(denormalizationContext: ['groups' => ['post:update']]),
        // new Put(), // I don't use PUT, only PATCH
        new Delete(),
    ],
)]
#[ORM\Entity()]
class Publication
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[Groups(['post:read', 'post:update'])]
    #[ORM\Column(length: 255)]
    private string $title = '';

    #[Groups(['post:read', 'post:update'])]
    #[ORM\Column(type: Types::TEXT)]
    private string $resume = '';

    #[Groups(['post:read:full', 'post:update'])]
    #[ORM\Column(type: Types::TEXT)]
    private string $content = 'null';

    #[Groups(['post:read'])]
    #[ORM\Column]
    private ?DateTimeImmutable $createdAt;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class)]
    private Collection $comments;

    #[Groups(['post:read', 'post:create'])]
    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[Groups(['post:read'])]
    protected ?int $commentNb = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getResume(): string
    {
        return $this->resume;
    }

    public function setResume(string $resume): void
    {
        $this->resume = $resume;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): void
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }
    }

    public function removeComment(Comment $comment): void
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    public function getCommentNb(): ?int
    {
        return $this->commentNb;
    }

    public function setCommentNb(int $commentNb): void
    {
        $this->commentNb = $commentNb;
    }
}
