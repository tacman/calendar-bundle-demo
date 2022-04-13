<?php

namespace App\Factory;

use App\Entity\Feed;
use App\Repository\FeedRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Feed>
 *
 * @method static Feed|Proxy createOne(array $attributes = [])
 * @method static Feed[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Feed|Proxy find(object|array|mixed $criteria)
 * @method static Feed|Proxy findOrCreate(array $attributes)
 * @method static Feed|Proxy first(string $sortedField = 'id')
 * @method static Feed|Proxy last(string $sortedField = 'id')
 * @method static Feed|Proxy random(array $attributes = [])
 * @method static Feed|Proxy randomOrCreate(array $attributes = [])
 * @method static Feed[]|Proxy[] all()
 * @method static Feed[]|Proxy[] findBy(array $attributes)
 * @method static Feed[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Feed[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static FeedRepository|RepositoryProxy repository()
 * @method Feed|Proxy create(array|callable $attributes = [])
 */
final class FeedFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }


    private function getAFeed(): iterable
    {
        static $i = 0;
        foreach ([
            'http://www.mysportscal.com/download-major-league-schedules/mlb/',
            'https://www.officeholidays.com/subscribe/usa'
                 ] as $url) {
            yield $url;
        }
    }
    protected function getDefaults(): array
    {
        return [
            'url' => $this->getAFeed(),
            'marking' => Feed::PLACE_NEW
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Feed $feed): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Feed::class;
    }
}
