<?php

namespace App\Entity\Auth;

use AllowDynamicProperties;
use App\Controller\FranchiseUsersAdminAction;
use App\Controller\RegisterMemberAction;
use App\Entity\Shop;
use ApiPlatform\Metadata\ApiProperty;
use DateTime;
use App\Entity\Media;
use App\Controller\ActivateAction;
use App\Controller\RegisterAction;
use App\Controller\UserController;
use App\Entity\Booking;
use App\Entity\Payment;
use App\Entity\Request;
use App\Entity\Schedule;
use App\Entity\Vacation;
use App\Entity\Franchise;
use App\Entity\Blog\Comment;
use App\Entity\Notification;
use App\Entity\Shop\Product;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Ramsey\Uuid\Rfc4122\UuidV4;
use App\Entity\Blog\Publication;
use Doctrine\ORM\Mapping as ORM;
use App\State\UserPasswordHasher;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use App\Constants\Groups as ConstantsGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[AllowDynamicProperties] #[ORM\Entity()]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
    new GetCollection(
                uriTemplate: '/vacations/{id}/users',
            ),
        new Get(
            uriTemplate: '/me',
            controller: UserController::class,
            denormalizationContext: ['groups' => ['user:read', 'schedule:read']],
            read: false,
        ),
        new Get(
            uriTemplate: '/activate/{token}/{user}',
            controller: ActivateAction::class,
            read: false,
        ),
        new GetCollection(),
                new GetCollection(
                    uriTemplate: '/vacations/{id}/users',

                ),
                new GetCollection(
                    uriTemplate: '/franchises/{id}/users',
                    controller: FranchiseUsersAdminAction::class,
                    read: false,
                ),
        new Post(denormalizationContext: ['groups' => ['user:write']]),
        new Post(
            uriTemplate: '/register',
            controller: RegisterAction::class,
            read: false,
        ),
        new Post(
            uriTemplate: '/register/member',
            controller: RegisterMemberAction::class,
            denormalizationContext: ['groups' => ['shop:members:write', 'user:write']],
            read: false,
        ),
        new Get(normalizationContext: ['groups' => [ConstantsGroups::USER_READ, 'user:read:full']]),
        new Patch(denormalizationContext: ['groups' => ['user:write:update', ConstantsGroups::USER_WRITE]]),
        // new Put(), // I don't use PUT, only PATCH
        // new Delete(), // Disable DELETE method, do soft delete instead
    ],
    normalizationContext: ['groups' => [ConstantsGroups::USER_READ]],
    denormalizationContext: ['groups' => ['user:write:update', ConstantsGroups::USER_WRITE]],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(ConstantsGroups::ALL_READ)]
    private ?int $id = null;

    #[Groups([ConstantsGroups::USER_READ, ConstantsGroups::USER_WRITE, ConstantsGroups::USER_FRANCHISE_WRITE])]
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Publication::class)]
    #[Groups([ConstantsGroups::USER_READ])]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
    #[Groups([ConstantsGroups::USER_READ])]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'buyers')]
    #[Groups([ConstantsGroups::USER_READ])]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Booking::class)]
    #[Groups([ConstantsGroups::USER_READ])]
    private Collection $bookings;

    #[ORM\ManyToMany(targetEntity: Payment::class, mappedBy: 'user')]
    #[Groups([ConstantsGroups::USER_READ])]
    private Collection $payments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vacation::class)]
    #[Groups([ConstantsGroups::USER_READ, "shop:members:read"])]
    private Collection $vacations;

    #[ORM\ManyToMany(targetEntity: Notification::class, mappedBy: 'users')]
    #[Groups([ConstantsGroups::USER_READ])]
    private Collection $notifications;

    #[ORM\ManyToMany(targetEntity: Franchise::class, mappedBy: 'users')]
    #[Groups([ConstantsGroups::USER_READ, ConstantsGroups::USER_FRANCHISE_WRITE])]
    private Collection $franchises;

    #[Groups([
        ConstantsGroups::USER_READ,
        ConstantsGroups::USER_WRITE,
        ConstantsGroups::FRANCHISE_READ,
        ConstantsGroups::SHOP_READ,
        ConstantsGroups::BIKE_READ,
        ConstantsGroups::REQUEST_READ,
        ConstantsGroups::USER_FRANCHISE_WRITE
    ])]
    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[Groups([
        ConstantsGroups::USER_READ,
        ConstantsGroups::USER_WRITE,
        ConstantsGroups::USER_FRANCHISE_WRITE,
        ConstantsGroups::FRANCHISE_READ,
        ConstantsGroups::SHOP_READ,
        ConstantsGroups::BIKE_READ,
        ConstantsGroups::REQUEST_READ
    ])]
    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[Groups([
        ConstantsGroups::USER_READ,
        ConstantsGroups::USER_WRITE,
        ConstantsGroups::USER_FRANCHISE_WRITE,
        ConstantsGroups::FRANCHISE_READ,
        ConstantsGroups::SHOP_READ,
        ConstantsGroups::BIKE_READ,
        ConstantsGroups::REQUEST_READ
    ])]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email = null;

    #[Groups([ConstantsGroups::USER_WRITE, ConstantsGroups::USER_FRANCHISE_WRITE])]
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[Groups([
        ConstantsGroups::USER_READ,
        ConstantsGroups::USER_WRITE,
        ConstantsGroups::USER_FRANCHISE_WRITE,
        ConstantsGroups::FRANCHISE_READ,
        ConstantsGroups::SHOP_READ,
        ConstantsGroups::BIKE_READ,
        ConstantsGroups::REQUEST_READ
    ])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[Groups([ConstantsGroups::USER_READ, ConstantsGroups::USER_WRITE, ConstantsGroups::USER_FRANCHISE_WRITE])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locale = null;

    #[Groups([ConstantsGroups::USER_READ, ConstantsGroups::USER_WRITE, ConstantsGroups::SHOP_READ])]
    #[ORM\Column]
    private ?bool $status = null;

    ##[Groups([ConstantsGroups::USER_WRITE])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $verification_key = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $token = null;

    #[ORM\ManyToMany(targetEntity: Schedule::class, inversedBy: 'users')]
    #[Groups([ConstantsGroups::USER_READ, 'shop:members:read'])]
    private Collection $schedules;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups([ConstantsGroups::USER_READ, ConstantsGroups::REQUEST_READ, ConstantsGroups::USER_WRITE])]
    private ?Media $media = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    #[Groups([ConstantsGroups::USER_READ])]
    private ?Request $request = null;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Notification::class)]
    private Collection $notification;

    #[ORM\ManyToMany(targetEntity: Shop::class, inversedBy: 'users')]
    #[Groups([ConstantsGroups::USER_READ])]
    private Collection $shops;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->bookings = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->vacations = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->franchises = new ArrayCollection();
        $this->verification_key = UuidV4::uuid4()->toString();
        $this->schedules = new ArrayCollection();
        $this->notification = new ArrayCollection();
        $this->shops = new ArrayCollection();
    }

    public function addShop(Shop $shop): static
    {
        if (!$this->shops->contains($shop)) {
            $this->shops->add($shop);
        }

        return $this;
    }

    public function getShops(): Collection
    {
        return $this->shops;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Publication $post): void
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }
    }

    public function removePost(Publication $post): void
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): void
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }
    }

    public function removeComment(Comment $comment): void
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): void
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addBuyer($this);
        }
    }

    public function removeProduct(Product $product): void
    {
        if ($this->products->removeElement($product)) {
            $product->removeBuyer($this);
        }
    }

    public function hasProduct(Product $object): bool
    {
        foreach ($this->products as $product) {
            if ($product->getId() === $object->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setUser($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getUser() === $this) {
                $booking->setUser(null);
            }
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
            $payment->adduser($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            $payment->removeuser($this);
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
            $vacation->setUser($this);
        }

        return $this;
    }

    public function removeVacation(Vacation $vacation): static
    {
        if ($this->vacations->removeElement($vacation)) {
            // set the owning side to null (unless already changed)
            if ($vacation->getUser() === $this) {
                $vacation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->addUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            $notification->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Franchise>
     */
    public function getFranchises(): Collection
    {
        return $this->franchises;
    }

    public function addFranchise(Franchise $franchise): static
    {
        if (!$this->franchises->contains($franchise)) {
            $this->franchises->add($franchise);
            $franchise->addUser($this);
        }

        return $this;
    }

    public function removeFranchise(Franchise $franchise): static
    {
        if ($this->franchises->removeElement($franchise)) {
            $franchise->removeUser($this);
        }

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getVerificationKey(): ?string
    {
        return $this->verification_key;
    }

    public function setVerificationKey(?string $verification_key): static
    {
        $this->verification_key = $verification_key;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

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

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): static
    {
        $this->media = $media;

        return $this;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): static
    {
        // unset the owning side of the relation if necessary
        if ($request === null && $this->request !== null) {
            $this->request->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($request !== null && $request->getUser() !== $this) {
            $request->setUser($this);
        }

        $this->request = $request;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotification(): Collection
    {
        return $this->notification;
    }
}
