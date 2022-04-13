<?php

namespace App\Workflow\Listener;

use App\Entity\Booking;
use App\Entity\Feed;
use Carbon\Carbon;
use ICal\ICal;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent;

/**
 * See all possible events in Symfony\Component\Workflow\Workflow.
 *
 * Symfony\Component\Workflow\Event\GuardEvent
 * state_machine.guard
 * state_machine.{workflow_name}.guard
 * state_machine.{workflow_name}.guard.{transition_name}
 *
 * Symfony\Component\Workflow\Event\Event
 * state_machine.transition #before transition
 * state_machine.{workflow_name}.transition
 * state_machine.{workflow_name}.transition.{transition_name}
 * state_machine.enter
 * state_machine.{workflow_name}.enter
 * state_machine.{workflow_name}.enter.{place_name}
 * state_machine.{workflow_name}.announce.{transition_name}
 * state_machine.leave
 * state_machine.{workflow_name}.leave.{place_name}
 */
class FeedTransitionListener implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    private Feed $entity;

    public function onGuard(GuardEvent $event)
    {
        $transition = $event->getTransition();

        /** @var Feed */
        $entity = $event->getSubject();
        $marking = $event->getMarking();
        $this->logger->info('onGuard', [$entity, $transition, $marking]);
//        $event->setBlocked(true);
    }

    public function onTransition(Event $event)
    {
        /** @var Feed */
        $entity = $event->getSubject();
        $transition = $event->getTransition();
        $marking = $event->getMarking();
        $this->logger->info('onTransition', [$entity, $transition, $marking]);
    }

    public function onFetchTransition(Event $event)
    {

        /** @var Feed $entity */
        $entity = $event->getSubject();
        dump($entity->getUrl());
        try {
            $ical = new ICal($entity->getUrl(), array(
                'defaultSpan'                 => 2,     // Default value
                'defaultTimeZone'             => 'UTC',
                'defaultWeekStart'            => 'MO',  // Default value
                'disableCharacterReplacement' => false, // Default value
                'filterDaysAfter'             => null,  // Default value
                'filterDaysBefore'            => null,  // Default value
                'skipRecurrence'              => false, // Default value
            ));
            // $ical->initFile('ICal.ics');
            // $ical->initUrl('https://raw.githubusercontent.com/u01jmg3/ics-parser/master/examples/ICal.ics', $username = null, $password = null, $userAgent = null);
        } catch (\Exception $e) {
            die($e);
        }
        foreach ($ical->events() as $icalEvent) {
            $booking = (new Booking())
                ->setTitle($icalEvent->summary)
                ->setBeginAt(Carbon::parse($icalEvent->dtstart))
            ;
//            dd($icalEvent, $booking);
            $entity->addBooking($booking);
        }

    }

    public function onEnterPlace(Event $event)
    {
        $entity = $event->getSubject();
        $transition = $event->getTransition();
        $marking = $event->getMarking();
    }

    public function onComplete(Event $event)
    {
        /** @var Feed */
        $entity = $event->getSubject();
        $transition = $event->getTransition();
        $marking = $event->getMarking();
    }

    public function onLeavePlace(Event $event)
    {
        $entity = $event->getSubject();
        $transition = $event->getTransition();
        $marking = $event->getMarking();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.feed.guard' => 'onGuard',
            'workflow.feed.guard.' . Feed::TRANSITION_AUTO => 'onGuard',
            'workflow.feed.guard.' . Feed::TRANSITION_MANUAL => 'onGuard',
            'workflow.feed.guard.' . Feed::TRANSITION_FETCH => 'onGuard',
            'workflow.feed.guard.' . Feed::TRANSITION_ARCHIVE => 'onGuard',

            'workflow.feed.transition' => 'onTransition',
            'workflow.feed.transition.' . Feed::TRANSITION_AUTO => 'onTransition',
            'workflow.feed.transition.' . Feed::TRANSITION_MANUAL => 'onTransition',
            'workflow.feed.transition.' . Feed::TRANSITION_FETCH => 'onFetchTransition',
            'workflow.feed.transition.' . Feed::TRANSITION_ARCHIVE => 'onTransition',

            'workflow.feed.complete' => 'onComplete',
            'workflow.feed.complete.' . Feed::TRANSITION_AUTO => 'onComplete',
            'workflow.feed.complete.' . Feed::TRANSITION_MANUAL => 'onComplete',
            'workflow.feed.complete.' . Feed::TRANSITION_FETCH => 'onComplete',
            'workflow.feed.complete.' . Feed::TRANSITION_ARCHIVE => 'onComplete',
        ];
    }
}
