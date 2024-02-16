<?php

namespace App\Entity;


use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Entity\Auth\User;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\BlameableTrait;
use App\Repository\FranchiseRepository;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Constants\Groups as ConstantsGroups;

#[ORM\Entity(repositoryClass: FranchiseRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => [ConstantsGroups::FRANCHISE_READ]],
)]
#[ApiFilter(SearchFilter::class, properties: ['createdBy' => 'exact'])]
class Franchise
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::FRANCHISE_READ, ConstantsGroups::SHOP_READ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        ConstantsGroups::FRANCHISE_READ,
        ConstantsGroups::SHOP_READ,
        ConstantsGroups::USER_READ,
        ConstantsGroups::REQUEST_READ,
        'request:validate'
    ])]
    private ?string $label = null;

    #[ORM\Column]
    #[Groups([ConstantsGroups::FRANCHISE_READ, ConstantsGroups::REQUEST_READ, 'request:validate'])]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'franchise', targetEntity: Shop::class, orphanRemoval: true)]
    #[Groups([ConstantsGroups::FRANCHISE_READ, ConstantsGroups::USER_READ, 'request:validate'])]
    private Collection $shops;

    #[ORM\OneToMany(mappedBy: 'franchise', targetEntity: Request::class)]
    #[Groups([ConstantsGroups::FRANCHISE_READ, ConstantsGroups::USER_READ])]
    private Collection $requests;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'franchises', cascade: ['persist'])]
    #[Groups([ConstantsGroups::FRANCHISE_READ])]
    private Collection $users;

    #[ORM\ManyToOne(inversedBy: 'franchises')]
    #[Groups([
        ConstantsGroups::FRANCHISE_READ,
        ConstantsGroups::SHOP_READ,
        ConstantsGroups::USER_READ,
    ])]
    private ?Media $media = null;

    public function __construct()
    {
        $this->shops = new ArrayCollection();
        $this->requests = new ArrayCollection();
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

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

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
            $shop->setFranchise($this);
        }

        return $this;
    }

    public function removeShop(Shop $shop): static
    {
        if ($this->shops->removeElement($shop)) {
            // set the owning side to null (unless already changed)
            if ($shop->getFranchise() === $this) {
                $shop->setFranchise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setFranchise($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getFranchise() === $this) {
                $request->setFranchise(null);
            }
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
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

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
