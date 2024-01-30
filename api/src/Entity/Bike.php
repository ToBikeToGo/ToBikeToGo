<?php

namespace App\Entity;

use App\Entity\Media;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Constants\Groups as ConstantsGroups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity()]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::BIKE_READ]]
)]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::BIKE_READ]],
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
                                    'shop' => [
                                        'type' => Shop::class,
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
            )
        )
    ]
)]
class Bike
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::BIKE_READ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::BIKE_READ, ConstantsGroups::SHOP_READ])]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::BIKE_READ, ConstantsGroups::SHOP_READ])]
    private ?string $label = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::BIKE_READ, ConstantsGroups::SHOP_READ])]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'bikes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    #[Groups([ConstantsGroups::BIKE_READ])]
    private Shop $shop;

    #[ORM\OneToMany(mappedBy: 'bike', targetEntity: Booking::class)]
    #[Groups([ConstantsGroups::BIKE_READ])]
    private Collection $bookings;

    #[ORM\ManyToMany(targetEntity: Proposition::class, inversedBy: 'bikes')]
    #[Groups([ConstantsGroups::BIKE_READ])]
    private Collection $propositions;

    #[ORM\ManyToOne(inversedBy: 'bikes')]
    #[Groups([ConstantsGroups::BIKE_READ, ConstantsGroups::MEDIA_READ, ConstantsGroups::SHOP_READ])]
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
}
