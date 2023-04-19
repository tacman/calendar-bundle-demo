<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Api\Filter\MultiFieldSearchFilter;
use App\Repository\FeedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Survos\CoreBundle\Entity\RouteParametersInterface;
use Survos\CoreBundle\Entity\RouteParametersTrait;

#[ORM\Entity(repositoryClass: FeedRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => [ 'minimum', 'rp']],
//    normalizationContext: ['groups' => ['Default', 'minimum', 'marking', 'browse', 'transitions', 'rp']],
    denormalizationContext: ['groups' => ["Default", "minimum", "browse"]],
)]
#[ApiFilter(OrderFilter::class, properties: ['marking', 'org', 'size', 'shortName', 'forkedFromId', 'fullName'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ["marking" => "exact", 'ownerType' => 'exact', 'fullName' => 'partial', 'forkedFromId' => 'exact', 'isZip' => 'exact'])]
#[ApiFilter(MultiFieldSearchFilter::class, properties: ['fullName', 'shortName'], arguments: ["searchParameterName" => "search"])]
class Feed implements RouteParametersInterface
{
    use RouteParametersTrait;

    const WORKFLOW = 'feed';
    const ICON = 'fas fa-chart-column';

    const PLACE_NEW = 'new';
    const PLACE_ICS_FETCHED = 'ics_fetched';
    const PLACE_AUTO = 'auto'; // can be configured.
    const PLACE_MANUAL = 'manual'; // can be configured.
    const PLACE_ARCHIVED = 'archived';

    const TRANSITION_AUTO= 'auto';
    const TRANSITION_MANUAL= 'manual';

    const TRANSITION_FETCH = 'fetch_ics';
    const TRANSITION_ARCHIVE = 'archive';

    public function __construct()
    {
        $this->marking = self::PLACE_NEW;
        $this->bookings = new ArrayCollection();
    }

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

    #[ORM\Column(type: 'text', nullable: true)]
    private $content;

    #[ORM\OneToMany(mappedBy: 'feed', targetEntity: Booking::class, orphanRemoval: true, cascade: ['persist'])]
    private $bookings;

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
        return ['feedId' => $this->getSlug()];
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setFeed($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getFeed() === $this) {
                $booking->setFeed(null);
            }
        }

        return $this;
    }


}
