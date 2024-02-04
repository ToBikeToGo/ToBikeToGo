<?php

namespace App\Entity\Blog;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Auth\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Constants\Groups as ConstantsGroups;

#[ORM\Entity()]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::COMMENT_READ]],
    denormalizationContext: ['groups' => [ConstantsGroups::COMMENT_WRITE]],
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Patch(),
        // new Put(), // I don't use PUT, only PATCH
        new Delete(),
    ]
)]
class Comment
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[Groups([ConstantsGroups::COMMENT_READ, ConstantsGroups::COMMENT_WRITE])]
    #[ORM\Column(length: 255)]
    private string $content = '';

    #[Groups([ConstantsGroups::COMMENT_READ])]
    #[ORM\Column]
    private DateTime $createdAt;

    #[Groups([ConstantsGroups::COMMENT_WRITE])]
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Publication $post = null;

    #[Groups([ConstantsGroups::COMMENT_READ, ConstantsGroups::COMMENT_WRITE])]
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getPost(): ?Publication
    {
        return $this->post;
    }

    public function setPost(?Publication $post): void
    {
        $this->post = $post;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }
}
