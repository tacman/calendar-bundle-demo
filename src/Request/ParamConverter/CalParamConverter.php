<?php
declare(strict_types=1);

namespace App\Request\ParamConverter;

use App\Entity\Cal;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CalParamConverter implements ParamConverterInterface
{
    public function __construct(private ManagerRegistry $registry)
    {
    }

    /**
     * {@inheritdoc}
     *
     * Check, if object supported by our converter
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Cal::class == $configuration->getClass();
    }

    /**
     * {@inheritdoc}
     *
     * Applies converting
     *
     * @throws \InvalidArgumentException When route attributes are missing
     * @throws NotFoundHttpException     When object not found
     * @throws Exception
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $params = $request->attributes->get('_route_params');

//        if (isset($params['calId']) && ($calId = $request->attributes->get('calId')))

        $calId = $request->attributes->get('calId');
        if ($calId === 'undefined') {
            throw new Exception("Invalid calId " . $calId);
        }

        // Check, if route attributes exists
        if (null === $calId ) {
            if (!isset($params['calId'])) {
                return false; // no calId in the route, so leave.  Could throw an exception.
            }
        }

        // Get actual entity manager for class.  We can also pass it in, but that won't work for the doctrine tree extension.
        $repository = $this->registry->getManagerForClass($configuration->getClass())?->getRepository($configuration->getClass());

        // Try to find the entity
        if (!$cal = $repository->findOneBy(['id' => $calId])) {
            throw new NotFoundHttpException(sprintf('%s %s object not found.', $calId, $configuration->getClass()));
        }

        // Map found cal to the route's parameter
        $request->attributes->set($configuration->getName(), $cal);
        return true;
    }

}
