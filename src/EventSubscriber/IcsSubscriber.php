<?php

namespace App\EventSubscriber;

use App\Repository\BookingRepository;
use App\Service\CalendarService;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IcsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private CalendarService $calendarService,
        private UrlGeneratorInterface $router
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        if (!array_key_exists('icsUrl', $filters)) {
            return;
        }

        $icsUrl = $filters['icsUrl'];

        try {
            $icsCalendar = $this->calendarService->loadByUrl($icsUrl);
        } catch (\Exception $exception) {
            return;
        }
        $events = $icsCalendar->getEvents();
        foreach ($events as $icsEvent) {
            // this create the events with your data (here booking data) to fill calendar
            $event  = new Event(
                $icsEvent['SUMMARY'],
                new \DateTimeImmutable($icsEvent['DTSTAMP'])
            );

            $event->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($event);
        }
    }
}
