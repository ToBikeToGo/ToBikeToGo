<?php

namespace App\Entity;

use App\Entity\Auth\User;
use ApiPlatform\Metadata\Link;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ScheduleRepository;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Constants\Groups as ConstantsGroups;

#[ORM\Entity()]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::SCHEDULE_READ]],
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/shops/{shopId}/schedules",
            uriVariables: [
                "shopId" => new Link(
                    fromClass: Shop::class,
                    fromProperty: "schedules"
                )
            ],
        ),
        new GetCollection(
            uriTemplate: "/users/{userId}/schedules",
            uriVariables: [
                "userId" => new Link(
                    fromClass: User::class,
                    fromProperty: "schedules"
                )
            ],
        )
    ]
)]
class Schedule
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::SCHEDULE_READ])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::SCHEDULE_READ, ConstantsGroups::SHOP_READ])]
    private ?int $dow = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups([ConstantsGroups::SCHEDULE_READ, ConstantsGroups::SHOP_READ])]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups([ConstantsGroups::SCHEDULE_READ, ConstantsGroups::SHOP_READ])]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\ManyToMany(targetEntity: Shop::class, mappedBy: 'schedules')]
    #[Groups([ConstantsGroups::SCHEDULE_READ])]
    private Collection $shops;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'schedules')]
    #[Groups([ConstantsGroups::SCHEDULE_READ])]
    private Collection $users;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups([ConstantsGroups::SCHEDULE_READ, ConstantsGroups::SHOP_READ])]
    private ?\DateTimeInterface $startValidity = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups([ConstantsGroups::SCHEDULE_READ, ConstantsGroups::SHOP_READ])]
    private ?\DateTimeInterface $endValidity = null;

    public function __construct()
    {
        $this->shops = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDow(): ?int
    {
        return $this->dow;
    }

    public function setDow(int $dow): static
    {
        $this->dow = $dow;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTime $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTime $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * @return Collection<int, Shop>
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    public function addShop(Shop $shop): static
    {
        if (!$this->shops->contains($shop)) {
            $this->shops->add($shop);
            $shop->addSchedule($this);
        }

        return $this;
    }

    public function removeShop(Shop $shop): static
    {
        if ($this->shops->removeElement($shop)) {
            $shop->removeSchedule($this);
        }

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
            $user->addSchedule($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeSchedule($this);
        }

        return $this;
    }

    public function getStartValidity(): ?\DateTimeInterface
    {
        return $this->startValidity;
    }

    public function setStartValidity(?\DateTimeInterface $startValidity): static
    {
        $this->startValidity = $startValidity;

        return $this;
    }

    public function getEndValidity(): ?\DateTimeInterface
    {
        return $this->endValidity;
    }

    public function setEndValidity(?\DateTimeInterface $endValidity): static
    {
        $this->endValidity = $endValidity;

        return $this;
    }
}
