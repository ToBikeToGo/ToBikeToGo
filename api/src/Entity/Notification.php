<?php

namespace App\Entity;

use App\Entity\Auth\User;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity()]
#[ApiResource]
class Notification
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $notificationType = null;

    #[ORM\Column(length: 255)]
    private ?string $sender = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $sentDate = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'notifications')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotificationType(): ?string
    {
        return $this->notificationType;
    }

    public function setNotificationType(string $notificationType): static
    {
        $this->notificationType = $notificationType;

        return $this;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(string $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getSentDate(): ?\DateTimeImmutable
    {
        return $this->sentDate;
    }

    public function setSentDate(\DateTimeImmutable $sentDate): static
    {
        $this->sentDate = $sentDate;

        return $this;
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
}
