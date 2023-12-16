<?php

namespace App\Entity;

use App\Dto\SlotsDto;
use App\Entity\Auth\User;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
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

#[ORM\Entity()]
#[ApiResource]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/bikes/{bikeId}/bookings",
            uriVariables: [
                "bikeId" => new Link(
                    fromClass: Bike::class,
                    fromProperty: "bookings"
                )
            ],
            normalizationContext: [
                'groups' => ['booking:read']
            ],
        ),
        new Post(
            input: SlotsDto::class,
            processor: SlotsProcessor::class,
            uriTemplate: "/shops/{id}/slots",
            controller: GetSlotsAction::class,
            read: false,
            openapi: new Operation(
                summary: "Get slots for a shop by date",
                tags: ["Slot"],
                requestBody: new RequestBody(
                    description: "Date to get slots for",
                    required: true,
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
                    )
                ),
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
            ),
        )
    ]
)]
class Booking
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['booking:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['booking:read'])]
    private ?\DateTime $startDate = null;

    #[ORM\Column]
    #[Groups(['booking:read'])]
    private ?\DateTime $endDate = null;

    #[ORM\Column]
    private ?float $rating = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?Bike $bike = null;

    #[ORM\OneToOne(mappedBy: 'booking', cascade: ['persist', 'remove'])]
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
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
