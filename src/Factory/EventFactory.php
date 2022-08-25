<?php

namespace App\Factory;

use App\Entity\Event;
use App\Repository\EventRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Event>
 *
 * @method static Event|Proxy createOne(array $attributes = [])
 * @method static Event[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Event|Proxy find(object|array|mixed $criteria)
 * @method static Event|Proxy findOrCreate(array $attributes)
 * @method static Event|Proxy first(string $sortedField = 'id')
 * @method static Event|Proxy last(string $sortedField = 'id')
 * @method static Event|Proxy random(array $attributes = [])
 * @method static Event|Proxy randomOrCreate(array $attributes = [])
 * @method static Event[]|Proxy[] all()
 * @method static Event[]|Proxy[] findBy(array $attributes)
 * @method static Event[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Event[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static EventRepository|RepositoryProxy repository()
 * @method Event|Proxy create(array|callable $attributes = [])
 */
final class EventFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $startTime =  new \DateTimeImmutable(sprintf('-%d days +%d hours', rand(1, 60), rand(2,24)));
        $endTime = $startTime->add(new \DateInterval("PT30M"));
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'startTime' => $startTime,
            'endTime' => rand(0, 8) <= 7 ? $startTime->add(new \DateInterval(sprintf("PT%dM", rand(1,4) * 30))) : null,
            'title' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Event $event): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Event::class;
    }
}
