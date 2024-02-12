<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Controller\GetBookingsByShopAction;
use App\Dto\SlotsDto;
use App\Entity\Auth\User;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Processor\SlotsProcessor;
use App\Controller\GetSlotsAction;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use App\Entity\Traits\TimestampableTrait;
use ApiPlatform\OpenApi\Model\RequestBody;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Constants\Groups as ConstantsGroups;

#[ORM\Entity()]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::BOOKING_READ]],
)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/shops/{id}/bookings',
                controller: GetBookingsByShopAction::class,
                name: 'booking_by_shop'
            ),
        new GetCollection(
            uriTemplate: "/bikes/{bikeId}/bookings",
            uriVariables: [
                "bikeId" => new Link(
                    fromClass: Bike::class,
                    fromProperty: "bookings"
                )
            ],
            normalizationContext: [
                'groups' => [ConstantsGroups::BOOKING_READ]
            ],
        ),
              new GetCollection(
                  uriTemplate: "/bookings/{shopId}",
            uriVariables: [
                "shopId" => new Link(
            fromProperty: "bikes",
                    fromClass: Shop::class
        )
            ],
            normalizationContext: [
                'groups' => [ConstantsGroups::BOOKING_READ]
            ],
        ),
        new Post(
            uriTemplate: "/shops/{id}/slots",
            controller: GetSlotsAction::class,
            openapi: new Operation(
                tags: ["Slot"],
                responses: [
                    '200' => [
                        'description' => 'Slots for a shop',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        // Array of slots for a shop
                                        '$ref' => '#/components/schemas/Slot',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '400' => [
                        'description' => 'Invalid input',
                    ],
                    '404' => [
                        'description' => 'Shop not found',
                    ],
                    '422' => [
                        'description' => 'Invalid input',
                    ],
                ],
                summary: "Get slots for a shop by date",
                requestBody: new RequestBody(
                    description: "Date to get slots for",
                    content: new \ArrayObject(
                        [
                            "application/json" => [
                                "schema" => [
                                    "type" => "object",
                                    "properties" => [
                                        "date" => [
                                            "type" => "string",
                                            "format" => "date",
                                        ],
                                    ],
                                ],
                                "example" => [
                                    "date" => "2021-01-01",
                                ],
                            ],
                        ]
                    ),
                    required: true
                ),
            ),
            input: SlotsDto::class,
            read: false,
            processor: SlotsProcessor::class,
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['user', 'bike'])]
class Booking extends EntityRepository
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::BOOKING_READ])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::BOOKING_READ, ConstantsGroups::BIKE_READ])]
    private ?\DateTime $startDate = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::BOOKING_READ, ConstantsGroups::BIKE_READ])]
    private ?\DateTime $endDate = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::BOOKING_READ, ConstantsGroups::BIKE_READ])]
    private ?float $rating = null;

    #[ORM\Column(length: 255, type: 'boolean')]
    #[Groups([ConstantsGroups::BOOKING_READ, ConstantsGroups::BIKE_READ])]
    private ?bool $status = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([ConstantsGroups::BOOKING_READ, ConstantsGroups::BIKE_READ])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[Groups([ConstantsGroups::BOOKING_READ])]
    private ?Bike $bike = null;

    #[ORM\OneToOne(mappedBy: 'booking', cascade: ['persist', 'remove'])]
    #[Groups([ConstantsGroups::BOOKING_READ])]
    private ?Payment $payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getBike(): ?Bike
    {
        return $this->bike;
    }

    public function setBike(?Bike $bike): static
    {
        $this->bike = $bike;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        // unset the owning side of the relation if necessary
        if ($payment === null && $this->payment !== null) {
            $this->payment->setBooking(null);
        }

        // set the owning side of the relation if necessary
        if ($payment !== null && $payment->getBooking() !== $this) {
            $payment->setBooking($this);
        }

        $this->payment = $payment;

        return $this;
    }



}
