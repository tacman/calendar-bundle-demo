<?php

namespace App\DataFixtures;

use App\Factory\ContestFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ContestFactory::new()->createMany(20);

        $manager->flush();
    }
}
