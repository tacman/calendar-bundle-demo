<?php

namespace App\Factory;

use App\Entity\Contest;
use App\Repository\ContestRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Contest>
 *
 * @method static Contest|Proxy createOne(array $attributes = [])
 * @method static Contest[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Contest|Proxy find(object|array|mixed $criteria)
 * @method static Contest|Proxy findOrCreate(array $attributes)
 * @method static Contest|Proxy first(string $sortedField = 'id')
 * @method static Contest|Proxy last(string $sortedField = 'id')
 * @method static Contest|Proxy random(array $attributes = [])
 * @method static Contest|Proxy randomOrCreate(array $attributes = [])
 * @method static Contest[]|Proxy[] all()
 * @method static Contest[]|Proxy[] findBy(array $attributes)
 * @method static Contest[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Contest[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ContestRepository|RepositoryProxy repository()
 * @method Contest|Proxy create(array|callable $attributes = [])
 */
final class ContestFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'title' => self::faker()->text(),
            'startDate' => self::faker()->datetime(),
            'duration' => self::faker()->numberBetween(2,14),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Contest $contest): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Contest::class;
    }
}
