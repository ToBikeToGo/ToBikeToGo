<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Entity\Auth\User;
use App\Entity\Traits\BlameableTrait;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\MediaUploadAction;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Constants\Groups as ConstantsGroups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            controller: MediaUploadAction::class,
            deserialize: false,
            validationContext: ['groups' => [ConstantsGroups::MEDIA_WRITE]],
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
            )
        ),
    ],
    normalizationContext: ['groups' => [ConstantsGroups::MEDIA_READ]],
)]
#[Vich\Uploadable]
class Media
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ConstantsGroups::MEDIA_READ])]
    private ?int $id = null;

    #[Groups(
        ConstantsGroups::ALL_READ,
    )]
    private ?string $contentUrl = null;

    #[ORM\Column(length: 255)]
    #[Groups([ConstantsGroups::MEDIA_READ])]
    private ?string $name = null;

    #[Assert\NotNull(groups: ['media:write'])]
    #[Assert\File(
        maxSize: '2048k',
        mimeTypes: [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif',
            'image/bmp'
        ],
        groups: [ConstantsGroups::MEDIA_WRITE]
    )]
    #[Vich\UploadableField(mapping: 'media_upload', fileNameProperty: 'name')]
    private ?File $file = null;

    #[ORM\Column(length: 20)]
    private ?string $extension = null;

    #[ORM\OneToMany(mappedBy: 'media', targetEntity: Bike::class)]
    private Collection $bikes;

    #[ORM\OneToMany(mappedBy: 'media', targetEntity: Shop::class)]
    private Collection $shops;

    #[ORM\OneToMany(mappedBy: 'media', targetEntity: Franchise::class)]
    private Collection $franchises;

    #[ORM\OneToMany(mappedBy: 'media', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->bikes = new ArrayCollection();
        $this->shops = new ArrayCollection();
        $this->franchises = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): static
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setContentUrl(string $contentUrl): static
    {
        $this->contentUrl = $contentUrl;

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
            $bike->setMedia($this);
        }

        return $this;
    }

    public function removeBike(Bike $bike): static
    {
        if ($this->bikes->removeElement($bike) && $bike->getMedia() === $this){
            // set the owning side to null (unless already changed)
            $bike->setMedia(null);
        }

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
            $shop->setMedia($this);
        }

        return $this;
    }

    public function removeShop(Shop $shop): static
    {
        if ($this->shops->removeElement($shop) && $shop->getMedia() === $this) {
            // set the owning side to null (unless already changed)
            $shop->setMedia(null);
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
            $franchise->setMedia($this);
        }

        return $this;
    }

    public function removeFranchise(Franchise $franchise): static
    {
        if ($this->franchises->removeElement($franchise) && $franchise->getMedia() === $this) {
            // set the owning side to null (unless already changed)
            $franchise->setMedia(null);
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
            $user->setMedia($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user) && $user->getMedia() === $this) {
            // set the owning side to null (unless already changed)
            $user->setMedia(null);
        }

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }

}
