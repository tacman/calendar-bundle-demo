<?php

namespace App\Factory;

use App\Entity\Org;
use App\Repository\OrgRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Org>
 *
 * @method static Org|Proxy createOne(array $attributes = [])
 * @method static Org[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Org|Proxy find(object|array|mixed $criteria)
 * @method static Org|Proxy findOrCreate(array $attributes)
 * @method static Org|Proxy first(string $sortedField = 'id')
 * @method static Org|Proxy last(string $sortedField = 'id')
 * @method static Org|Proxy random(array $attributes = [])
 * @method static Org|Proxy randomOrCreate(array $attributes = [])
 * @method static Org[]|Proxy[] all()
 * @method static Org[]|Proxy[] findBy(array $attributes)
 * @method static Org[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Org[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static OrgRepository|RepositoryProxy repository()
 * @method Org|Proxy create(array|callable $attributes = [])
 */
final class OrgFactory extends ModelFactory
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
            'name' => self::faker()->text(),
            'slug' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Org $org): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Org::class;
    }
}
