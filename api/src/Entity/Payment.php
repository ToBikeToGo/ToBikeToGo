<?php

namespace App\Entity;

use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Constants\Groups as ConstantsGroups;
use App\Controller\PaymentAction;
use App\Entity\Auth\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PaymentRepository;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity()]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/payments/booking',
            controller: PaymentAction::class,
            denormalizationContext: ['groups' => [ConstantsGroups::PAYMENT_WRITE]],
            read: false
        ),
    ]
)]
class Payment
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups([ConstantsGroups::PAYMENT_WRITE, ConstantsGroups::BOOKING_READ])]
    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $commission = null;

    #[ORM\Column(length: 255)]
    private ?string $stripeId = null;

    #[ORM\Column(length: 255, nullable: true, type: 'boolean')]
    private ?bool $status = null;

    #[ORM\Column]
    private ?\DateTime $paymentDate = null;

    #[ORM\ManyToMany(targetEntity: Shop::class, inversedBy: 'payments')]
    private Collection $shop;

    #[Groups([ConstantsGroups::PAYMENT_WRITE])]
    #[ORM\OneToOne(inversedBy: 'payment', cascade: ['persist', 'remove'])]
    private ?Booking $booking = null;

    #[Groups([ConstantsGroups::PAYMENT_WRITE])]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'payments')]
    private Collection $user;

    public function __construct()
    {
        $this->shop = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCommission(): ?int
    {
        return $this->commission;
    }

    public function setCommission(int $commission): static
    {
        $this->commission = $commission;

        return $this;
    }

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(string $stripeId): static
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentDate(): ?\DateTime
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTime $paymentDate): static
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * @return Collection<int, Shop>
     */
    public function getShop(): Collection
    {
        return $this->shop;
    }

    public function addShop(Shop $shop): static
    {
        if (!$this->shop->contains($shop)) {
            $this->shop->add($shop);
        }

        return $this;
    }

    public function removeShop(Shop $shop): static
    {
        $this->shop->removeElement($shop);

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): static
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function adduser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeuser(User $user): static
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }
}
