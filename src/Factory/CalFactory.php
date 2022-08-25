<?php

namespace App\Factory;

use App\Entity\Cal;
use App\Repository\CalRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Cal>
 *
 * @method static Cal|Proxy createOne(array $attributes = [])
 * @method static Cal[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Cal|Proxy find(object|array|mixed $criteria)
 * @method static Cal|Proxy findOrCreate(array $attributes)
 * @method static Cal|Proxy first(string $sortedField = 'id')
 * @method static Cal|Proxy last(string $sortedField = 'id')
 * @method static Cal|Proxy random(array $attributes = [])
 * @method static Cal|Proxy randomOrCreate(array $attributes = [])
 * @method static Cal[]|Proxy[] all()
 * @method static Cal[]|Proxy[] findBy(array $attributes)
 * @method static Cal[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Cal[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CalRepository|RepositoryProxy repository()
 * @method Cal|Proxy create(array|callable $attributes = [])
 */
final class CalFactory extends ModelFactory
{
    private static  array $calTypes = ['Meetings', 'Public Events', 'Fundraisers'];
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'name' => self::faker()->name() // $this->getACalendar()
        ];
    }

    private function getACalendar(): iterable
    {
        static $types;
        if (empty($types)) {
            $types = clone self::$calTypes;
            shuffle($types);
        }
        dd($types);

        foreach ($types as $type) {
            dd($type);
            yield $type;
        }
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Cal $cal): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Cal::class;
    }
}
