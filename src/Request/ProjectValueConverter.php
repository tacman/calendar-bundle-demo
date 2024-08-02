<?php

declare(strict_types=1);

namespace App\Request;

use App\Command\ProjectCommandTrait;
use App\Entity\Catalog;
use App\Entity\Category;
use App\Entity\Device;
use App\Entity\Feed;
use App\Entity\OldCore;
use App\Entity\Field\CategoryField;
use App\Entity\Field\Field;
use App\Entity\Field\RelationField;
use App\Entity\FieldMap;
use App\Entity\FieldSet;
use App\Entity\Instance;
use App\Entity\Member;
use App\Entity\Owner;
use App\Entity\Package;
use App\Entity\Project;
use App\Entity\Core;
use App\Entity\Invitation;
use App\Entity\Repo;
use App\Entity\Sheet;
use App\Entity\SheetColumn;
use App\Entity\Spreadsheet;
use App\Request\AppParameterTrait;
use App\Service\CatalogService;
use App\Service\ProjectService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Survos\CoreBundle\Entity\RouteParametersInterface;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\EventListener\LocaleListener;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ProjectValueConverter implements ValueResolverInterface
{
//    use AppParameterTrait;

    public function __construct(
        private ManagerRegistry $registry,
        protected LoggerInterface $logger,
        protected EntityManagerInterface $entityManager,
        protected CacheInterface $cache,
        protected ParameterBagInterface $bag
    ) {
    }


    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        // get the argument type (e.g. BookingId)
        $argumentType = $argument->getType();

        // get the value from the request, based on the argument name
        $value = $request->attributes->get($argument->getName());

        if (!is_subclass_of($argumentType, RouteParametersInterface::class)) {
            return [];
        }
        // the catalog classes are not persisted but may be loaded.  Here??
        if (!$em = $this->registry->getManagerForClass($argumentType)) {
            return [];
        }
        $repository = $em->getRepository($argumentType);

        $shortName = (new \ReflectionClass($argumentType))->getShortName();
        // not lovely...
        if ($shortName == 'Repo') {
            $idField = 'githubId';
        } else {
            $idField = lcfirst($shortName) . 'Id'; // e.g. projectId
        }


        if ($request->attributes->has($idField)) {
            $idFieldValue = $request->attributes->get($idField);
        } else {
            $idFieldValue = null;
            $this->logger->warning(sprintf("%s not found in %s", $idField, $argumentType));
            dd($idField, $request->attributes);
        }
//        if ($argumentType == Catalog::class) {
//            assert(false, "load catalog on demand here??");
//            dd($value, $idFieldValue);
//        }

        $value = match ($argumentType) {
            Feed::class => $repository->findOneBy(['slug' => $idFieldValue]),
            default => assert(false, "$argumentType not handled")
        };

        // explicitly set the argument value for later reuse
        $request->attributes->set($argument->getName(), $value);
        return [$value];
    }
}
