<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Survos\BaseBundle\Entity\SurvosBaseEntity;
use Survos\CoreBundle\Entity\RouteParametersInterface;
use Survos\CoreBundle\Entity\RouteParametersTrait;
use Survos\WorkflowBundle\Traits\MarkingInterface;
use Survos\WorkflowBundle\Traits\MarkingTrait;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource]
class Event implements RouteParametersInterface
{
    use RouteParametersTrait;
    const PLACE_NEW = 'new';

    public function __construct()
    {
        $this->marking = self::PLACE_NEW;
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Cal::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $cal;

    #[ORM\Column(type: 'json_document', nullable: true)]
    private $icalEvent;

    #[ORM\Column(type: 'datetime')]
    private $startTime;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $endTime;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCal(): ?Cal
    {
        return $this->cal;
    }

    public function setCal(?Cal $cal): self
    {
        $this->cal = $cal;

        return $this;
    }

    public function getIcalEvent()
    {
        return $this->icalEvent;
    }

    public function setIcalEvent($icalEvent): self
    {
        $this->icalEvent = $icalEvent;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
