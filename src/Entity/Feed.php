<?php

namespace App\Entity;

use App\Repository\FeedRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Survos\BaseBundle\Entity\SurvosBaseEntity;

#[ORM\Entity(repositoryClass: FeedRepository::class)]
class Feed extends SurvosBaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $url;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $eventCount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['url'])]
    private string $slug;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Feed
     */
    public function setSlug(string $slug): Feed
    {
        $this->slug = $slug;
        return $this;
    }


    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getEventCount(): ?int
    {
        return $this->eventCount;
    }

    public function setEventCount(?int $eventCount): self
    {
        $this->eventCount = $eventCount;

        return $this;
    }

    function getUniqueIdentifiers(): array
    {
        return ['slug' => $this->getSlug()];
    }


}
