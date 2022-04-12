<?php

namespace App\Entity;

use App\Repository\FeedRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Survos\BaseBundle\Entity\SurvosBaseEntity;
use Survos\WorkflowBundle\Traits\MarkingInterface;
use Survos\WorkflowBundle\Traits\MarkingTrait;

#[ORM\Entity(repositoryClass: FeedRepository::class)]
class Feed extends SurvosBaseEntity implements MarkingInterface
{
    const WORKFLOW = 'feed';
    use MarkingTrait;
    const ICON = 'fas fa-chart-column';

    const PLACE_NEW = 'new';
    const PLACE_BILLS_FETCHED = 'bills_fetched';
    const PLACE_AUTO = 'auto'; // can be configured.
    const PLACE_MANUAL = 'manual'; // can be configured.
    const PLACE_ARCHIVED = 'archived';

    const TRANSITION_AUTO= 'auto';
    const TRANSITION_MANUAL= 'manual';

    const TRANSITION_TRACK_BILLS = 'select_bills';
    const TRANSITION_SCORE_ACTIONS = 'select_actions';
    const TRANSITION_FETCH_BILLS = 'fetch_bills';
    const TRANSITION_LEGISLATORS = 'fetch_legislators';
    const TRANSITION_ARCHIVE = 'archive';

    public function __construct()
    {
        $this->marking = self::PLACE_NEW;
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


}
