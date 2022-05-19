<?php

namespace App\DataFixtures;

use App\Entity\Cal;
use App\Entity\Feed;
use App\Factory\BookingFactory;
use App\Factory\CalFactory;
use App\Factory\ContestFactory;
use App\Factory\FeedFactory;
use App\Factory\OrgFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
//        ContestFactory::new()->createMany(20);
        OrgFactory::new()->createMany(20);
        CalFactory::createMany(
            80,
            function() {
                return ['org' => OrgFactory::random()];
            }
        );

        foreach ([
            'https://www.officeholidays.com/ics-all/usa',
            'http://www.castletonfire.com/ical.html?from=calendar&id=47525',
            'https://amissvillevfr.org/ical.html?from=calendar&id=73874',
            'http://www.mysportscal.com/wp-content/uploads/2015/03/washington-nationals1.ics',
                     'http://www.mysportscal.com/wp-content/uploads/2015/03/MLB_2012_Complete.ics',
                     'https://www.officeholidays.com/subscribe/usa'
                 ] as $url) {
            $feed = (new Feed())
                ->setUrl($url);

            $manager->persist($feed);
        }
        $manager->flush();
    }
}
