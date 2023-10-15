<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ShopRepository;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ShopRepository::class)]
#[ApiResource]
class Shop
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?bool $isOpened = null;

    #[ORM\OneToMany(mappedBy: 'shop', targetEntity: Bike::class, orphanRemoval: true)]
    private Collection $bikes;

    #[ORM\ManyToOne(inversedBy: 'shops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Franchise $franchise = null;

    #[ORM\ManyToMany(targetEntity: Schedule::class, mappedBy: 'shops')]
    private Collection $schedules;

    #[ORM\ManyToMany(targetEntity: Payment::class, mappedBy: 'shop')]
    private Collection $payments;

    #[ORM\OneToMany(mappedBy: 'shop', targetEntity: Vacation::class)]
    private Collection $vacations;

    public function __construct()
    {
        $this->bikes = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->vacations = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

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
        if ($this->bikes->removeElement($bike)) {
            // set the owning side to null (unless already changed)
            if ($bike->getShop() === $this) {
                $bike->setShop(null);
            }
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
            $schedule->addShop($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): static
    {
        if ($this->schedules->removeElement($schedule)) {
            $schedule->removeShop($this);
        }

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

    public function addVacation(Vacation $vacation): static
    {
        if (!$this->vacations->contains($vacation)) {
            $this->vacations->add($vacation);
            $vacation->setShop($this);
        }

        return $this;
    }

    public function removeVacation(Vacation $vacation): static
    {
        if ($this->vacations->removeElement($vacation)) {
            // set the owning side to null (unless already changed)
            if ($vacation->getShop() === $this) {
                $vacation->setShop(null);
            }
        }

        return $this;
    }
}
