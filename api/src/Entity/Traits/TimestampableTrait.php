<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Component\Serializer\Attribute\Groups;

trait TimestampableTrait
{
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP', 'nullable' => true])]
    #[Timestampable(on: 'create')]
    private ?\DateTime $createdAt = null;
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP', 'nullable' => true])]
    #[Timestampable(on: 'update')]
    private ?\DateTime $updatedAt = null;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     * @return self
     */
    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     * @return self
     */
    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
