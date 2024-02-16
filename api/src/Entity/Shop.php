<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Controller\ShopStatsAction;
use App\Entity\Auth\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ShopRepository;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Constants\Groups as ConstantsGroups;

#[ORM\Entity()]
#[ApiResource(
    uriTemplate: '/shops/vacations/{id}',
    operations: [new Get()],
    normalizationContext: ['groups' => [ConstantsGroups::SHOP_VACATIONS_READ]],
)]
#[ApiResource(
    uriTemplate: '/shops/members/{id}',
    operations: [new Get()],
    normalizationContext: ['groups' => [ConstantsGroups::SHOP_READ, 'shop:members:read']],
)]
#[ApiResource(
    operations: [new GetCollection(
        uriTemplate: "/franchises/{franchiseId}/shops",
        uriVariables: [
            "franchiseId" => new Link(
                fromClass: Franchise::class,
                fromProperty: "shops"
            )
        ],
    )]
)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: "/shops/{id}/stats",
            controller: ShopStatsAction::class,
            normalizationContext: ['groups' => [ConstantsGroups::FRANCHISE_READ]]
        )
    ],
)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: "/shops",
            normalizationContext: ['groups' => [ConstantsGroups::SHOP_WRITE]],
        )
    ],
)]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::SHOP_READ]],
)]
#[ApiFilter(SearchFilter::class, properties: ['label' => 'partial'])]
class Shop
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ ConstantsGroups::BIKE_READ,ConstantsGroups::SHOP_WRITE,
        ConstantsGroups::FRANCHISE_READ, ConstantsGroups::SHOP_MEMBERS_READ, ConstantsGroups::SHOP_READ,'read:shop:test'])]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'shops')]
    #[Groups(ConstantsGroups::SHOP_READ)]
    private Collection $users;

    #[ORM\Column(length: 255)]
    #[Groups([
        'request:validate',ConstantsGroups::USER_READ, ConstantsGroups::BOOKING_READ, ConstantsGroups::REQUEST_READ, ConstantsGroups::SHOP_MEMBERS_READ,
        ConstantsGroups::BIKE_READ,
        ConstantsGroups::SHOP_READ,
        ConstantsGroups::FRANCHISE_READ
    ])]
    private ?string $label = null;

    #[Groups(['read:shop:test',"request:read", "shop:members:read", ConstantsGroups::BIKE_READ, ConstantsGroups::SHOP_READ, ConstantsGroups::FRANCHISE_READ])]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::SHOP_READ, ConstantsGroups::FRANCHISE_READ, ConstantsGroups::BIKE_READ])]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::SHOP_READ, ConstantsGroups::FRANCHISE_READ, ConstantsGroups::BIKE_READ])]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::SHOP_READ, ConstantsGroups::FRANCHISE_READ, ConstantsGroups::BIKE_READ])]
    private ?string $city = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::SHOP_READ])]
    private ?bool $isOpened = null;

    #[ORM\OneToMany(mappedBy: 'shop', targetEntity: Bike::class, orphanRemoval: true)]
    #[Groups([ConstantsGroups::SHOP_READ])]
    private Collection $bikes;

    #[ORM\ManyToOne(inversedBy: 'shops')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([ConstantsGroups::SHOP_READ])]
    private ?Franchise $franchise = null;

    #[ORM\ManyToMany(targetEntity: Schedule::class, inversedBy: 'shops')]
    #[Groups([ConstantsGroups::SHOP_READ])]
    private Collection $schedules;

    #[ORM\ManyToMany(targetEntity: Payment::class, mappedBy: 'shop')]
    private Collection $payments;

    #[ORM\OneToMany(mappedBy: 'shop', targetEntity: Vacation::class)]
    private Collection $vacations;

    #[ORM\ManyToOne(inversedBy: 'shops')]
    private ?Media $media = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups([ConstantsGroups::SHOP_READ, ConstantsGroups::SHOP_WRITE])]
    private ?float $longitude = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups([ConstantsGroups::SHOP_READ, ConstantsGroups::SHOP_WRITE])]
    private ?float $latitude = null;

    public function __construct()
    {
        $this->bikes = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->vacations = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getIsOpened(): ?bool
    {
        return $this->isOpened;
    }

    public function setIsOpened(bool $isOpened): static
    {
        $this->isOpened = $isOpened;

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
            $bike->setShop($this);
        }

        return $this;
    }

    public function removeBike(Bike $bike): static
    {
        if ($this->bikes->removeElement($bike) && $bike->getShop() === $this) {
            // set the owning side to null (unless already changed)
            $bike->setShop(null);
        }

        return $this;
    }

    public function getFranchise(): ?Franchise
    {
        return $this->franchise;
    }

    public function setFranchise(?Franchise $franchise): static
    {
        $this->franchise = $franchise;

        return $this;
    }

    /**
     * @return Collection<int, Schedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): static
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): static
    {
        $this->schedules->removeElement($schedule);

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->addShop($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            $payment->removeShop($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Vacation>
     */
    public function getVacations(): Collection
    {
        return $this->vacations;
    }


    public function getUsers(): Collection
    {
        return $this->users;
    }


    public function addVacation(Vacation $vacation): static
    {
        if (!$this->vacations->contains($vacation)) {
            $this->vacations->add($vacation);
            $vacation->setShop($this);
        }

        return $this;
    }

    public function addUsers(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addShop($this);
        }

        return $this;
    }


   public function removeVacation(Vacation $vacation): static
     {
         if ($this->vacations->removeElement($vacation) && $vacation->getShop() === $this) {
             // set the owning side to null (unless already changed)
             $vacation->setShop(null);
         }

         return $this;
     }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): static
    {
        $this->media = $media;

        return $this;
    }


    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function isIsOpened(): ?bool
    {
        return $this->isOpened;
    }



    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }
}
