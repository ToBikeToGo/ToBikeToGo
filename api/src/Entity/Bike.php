<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\GetAvailableBikesInShopByDate;
use App\Controller\GetAvailableDateForABike;
use App\Controller\GetAvailableShopsWithBikeByDate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use ApiPlatform\OpenApi\Model;
use Doctrine\ORM\Mapping as ORM;
use App\Filter\CustomDateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Constants\Groups as ConstantsGroups;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Constants\Globals as Roles;

#[ORM\Entity()]
#[ApiFilter(
    BooleanFilter::class, properties: ['isElectric']
)]
#[ApiFilter(
    NumericFilter::class, properties: ['price']
)]
#[ApiFilter(
    RangeFilter::class, properties: ['price']
)]
#[ApiFilter(
    SearchFilter::class, properties: [
        'brand' => SearchFilter::STRATEGY_IPARTIAL,
        'label' => SearchFilter::STRATEGY_IPARTIAL,
        'category.name' => SearchFilter::STRATEGY_EXACT,
        'category.type' => SearchFilter::STRATEGY_EXACT,
        'shop.city' => SearchFilter::STRATEGY_IPARTIAL,
        'shop.zipCode' => SearchFilter::STRATEGY_EXACT,
    ]
)]
#[ApiFilter(
    CustomDateFilter::class, properties: ['startAt', 'endAt']
)]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::BIKE_READ]]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/shops/{shopId}/bikes",
            uriVariables: [
                "shopId" => new Link(
                    fromClass: Shop::class,
                    fromProperty: "bikes"
                )
            ],
        ),
        new Post(
            uriTemplate: '/bikes/available',
            controller: GetAvailableShopsWithBikeByDate::class,
            name: 'api_shops_available',
        ),
        new Get(
            uriTemplate: '/bikes/{id}/unavailable',
            controller: GetAvailableDateForABike::class,
            name: 'api_bikes_unavailable_dates'
        ),
        new Post(
            uriTemplate: '/bikes/available/shop/{id}',
            controller: GetAvailableBikesInShopByDate::class,
            name: 'api_bikes_available'
        ),
        new Post(
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'brand' => [
                                        'type' => 'string',
                                    ],
                                    'label' => [
                                        'type' => 'string',
                                    ],
                                    'price' => [
                                        'type' => 'number',
                                    ],
                                    'isElectric' => [
                                        'type' => 'boolean',
                                    ],
                                    'category' => [
                                        'type' => BikeCategory::class,
                                    ],
                                    'shop' => [
                                        'type' => Shop::class,
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
            )
        ),
        new GetCollection(
            uriTemplate: "/shops/{shopId}/bikes",
            uriVariables: [
                "shopId" => new Link(
                    fromProperty: "bikes",
                    fromClass: Shop::class
                )
            ],
            normalizationContext: ['groups' => [ConstantsGroups::BIKE_READ]]
        ),
    ]
)]
class Bike extends EntityRepository
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::BIKE_READ, ConstantsGroups::SHOP_READ, ConstantsGroups::BOOKING_READ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::BIKE_READ, ConstantsGroups::SHOP_READ, ConstantsGroups::BIKE_CATEGORY_READ])]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::BIKE_READ, ConstantsGroups::SHOP_READ, ConstantsGroups::BIKE_CATEGORY_READ])]
    private ?string $label = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::BIKE_READ,ConstantsGroups::BOOKING_READ,  ConstantsGroups::SHOP_READ, ConstantsGroups::BIKE_CATEGORY_READ])]
    private ?float $price = null;

    #[ORM\Column(options: ["default" => false])]
    #[Groups([ConstantsGroups::BOOKING_READ, ConstantsGroups::BIKE_READ, ConstantsGroups::SHOP_READ, ConstantsGroups::BIKE_CATEGORY_READ])]
    private ?bool $isElectric = false;

    #[ORM\ManyToOne(inversedBy: 'bikes')]
    #[Groups([ConstantsGroups::BIKE_READ, ConstantsGroups::SHOP_READ])]
    private ?BikeCategory $category = null;

    #[ORM\ManyToOne(inversedBy: 'bikes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    #[Groups([ConstantsGroups::BIKE_READ, ConstantsGroups::BOOKING_READ])]
    private Shop $shop;

    #[ORM\OneToMany(mappedBy: 'bike', targetEntity: Booking::class)]
    #[Groups([ConstantsGroups::BIKE_READ])]
    private Collection $bookings;

    #[ORM\ManyToMany(targetEntity: Proposition::class, inversedBy: 'bikes')]
    #[Groups([ConstantsGroups::BIKE_READ])]
    private Collection $propositions;

    #[ORM\ManyToOne(inversedBy: 'bikes')]
    #[Groups([
        ConstantsGroups::BIKE_READ,
        ConstantsGroups::MEDIA_READ,
        ConstantsGroups::SHOP_READ,
        ConstantsGroups::BOOKING_READ
    ])]
    private ?Media $media = null;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->propositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): static
    {
        $this->shop = $shop;

        return $this;
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
            $booking->setBike($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getBike() === $this) {
                $booking->setBike(null);
            }
        }

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
        }

        return $this;
    }

    public function removeProposition(Proposition $proposition): static
    {
        $this->propositions->removeElement($proposition);

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

    public function getCategory(): ?BikeCategory
    {
        return $this->category;
    }

    public function setCategory(?BikeCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function isIsElectric(): ?bool
    {
        return $this->isElectric;
    }

    public function setIsElectric(bool $isElectric): static
    {
        $this->isElectric = $isElectric;

        return $this;
    }
}
