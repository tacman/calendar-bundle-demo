<?php

namespace App\Entity;

use ApiPlatform\Metadata\Annotation\ApiFilter;
use ApiPlatform\Metadata\Annotation\ApiResource;
use ApiPlatform\Metadata\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Api\Filter\MultiFieldSearchFilter;
use App\Repository\CalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Survos\CoreBundle\Entity\RouteParametersInterface;
use Survos\CoreBundle\Entity\RouteParametersTrait;
use Survos\WorkflowBundle\Traits\MarkingInterface;
use Survos\WorkflowBundle\Traits\MarkingTrait;

#[ORM\Entity(repositoryClass: CalRepository::class)]
#[ORM\Table('calendar')]
#[ApiResource(
    normalizationContext: ['groups' => ['Default', 'minimum', 'marking', 'browse', 'transitions', 'rp']],
    denormalizationContext: ['groups' => ["Default", "minimum", "browse"]],
)]
#[ApiFilter(OrderFilter::class, properties: ['marking', 'name'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ["marking" => "exact", 'ownerType' => 'exact', 'fullName' => 'partial', 'forkedFromId' => 'exact', 'isZip' => 'exact'])]
#[ApiFilter(MultiFieldSearchFilter::class, properties: ['fullName', 'shortName'], arguments: ["searchParameterName" => "search"])]
class Cal implements RouteParametersInterface, \Stringable
{
    use RouteParametersTrait;

    const PLACE_NEW = 'new';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Gedmo\Slug(fields: ['name'])]
    private $slug;

    #[ORM\ManyToOne(targetEntity: Org::class, inversedBy: 'calendars')]
    #[ORM\JoinColumn(nullable: false)]
    private $org;

    #[ORM\OneToMany(mappedBy: 'cal', targetEntity: Event::class, orphanRemoval: true)]
    private $events;

    #[ORM\Column(type: 'integer')]
    private $eventCount;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->marking = self::PLACE_NEW;
        $this->eventCount = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getOrg(): ?Org
    {
        return $this->org;
    }

    public function setOrg(?Org $org): self
    {
        $this->org = $org;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setCal($this);
            $this->eventCount++;
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCal() === $this) {
                $event->setCal(null);
            }
            $this->eventCount--;
        }

        return $this;
    }

    public function getEventCount(): ?int
    {
        return $this->eventCount;
    }

    public function setEventCount(int $eventCount): self
    {
        $this->eventCount = $eventCount;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
