<?php

// config/packages/workflow.php
use App\Entity\Feed;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework) {
    $feedTracking = $framework->workflows()->workflows(Feed::WORKFLOW);
    $feedTracking
        ->type('state_machine') // or 'state_machine'
        ->supports([Feed::class])
        ->initialMarking([Feed::PLACE_NEW]);

    $feedTracking->auditTrail()->enabled(true);
    $feedTracking->markingStore()
        ->type('method')
        ->property('marking');

    $feedTracking->place()->name(Feed::PLACE_NEW);
    $feedTracking->place()->name(Feed::PLACE_AUTO);
    $feedTracking->place()->name(Feed::PLACE_MANUAL);
    $feedTracking->place()->name(Feed::PLACE_ARCHIVED);

    $feedTracking->transition()
        ->name(Feed::TRANSITION_FETCH)
        ->from([Feed::PLACE_NEW, Feed::PLACE_MANUAL, Feed::PLACE_AUTO])
        ->to([Feed::PLACE_AUTO]);

    $feedTracking->transition()
        ->name(Feed::TRANSITION_AUTO)
        ->from([Feed::PLACE_NEW, Feed::PLACE_MANUAL])
        ->to([Feed::PLACE_AUTO]);

    $feedTracking->transition()
        ->name(Feed::TRANSITION_MANUAL)
        ->from([Feed::PLACE_AUTO])
        ->to([Feed::PLACE_MANUAL]);

    $feedTracking->transition()
        ->name(Feed::TRANSITION_ARCHIVE)
        ->from([Feed::PLACE_MANUAL])
        ->to([Feed::PLACE_ARCHIVED]);

};
