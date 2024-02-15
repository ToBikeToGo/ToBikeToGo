<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use App\Constants\Groups as ConstantsGroups;
use App\Controller\NotificationAction;
use App\Entity\Auth\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity()]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/notifications',
            controller: NotificationAction::class,
            read: false,
        ),
        new GetCollection(normalizationContext: ['groups' => [ConstantsGroups::NOTIFICATION_READ]]),

    ],
)]
class Notification implements \Stringable
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'notifications')]
    private Collection $users;

    #[ORM\ManyToOne(inversedBy: 'notification')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([ConstantsGroups::NOTIFICATION_READ, ConstantsGroups::USER_READ])]
    private ?NotificationType $notificationType = null;

    #[ORM\ManyToOne(inversedBy: 'notification')]
    private ?User $sender = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::NOTIFICATION_READ, ConstantsGroups::USER_READ])]
    private ?string $text = null;

    #[ORM\Column(type: "datetime")]
    #[Groups([ConstantsGroups::NOTIFICATION_READ])]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updatedAt;
    #[ORM\Column(nullable: true)]
    private ?bool $isAlreadySeen = null;
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }
    public function __toString(): string
    {
        return (string)$this->getText();
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updated = new \DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotificationType(): ?NotificationType
    {
        return $this->notificationType;
    }

    public function setNotificationType(?NotificationType $notificationType): void
    {
        $this->notificationType = $notificationType;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function isIsAlreadySeen(): ?bool
    {
        return $this->isAlreadySeen;
    }

    public function setIsAlreadySeen(?bool $isAlreadySeen): static
    {
        $this->isAlreadySeen = $isAlreadySeen;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}
