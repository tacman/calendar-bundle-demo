<?php
declare(strict_types=1);

namespace App\Request\ParamConverter;

use App\Entity\Org;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class OrgParamConverter implements ParamConverterInterface
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
        return Org::class == $configuration->getClass();
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

//        if (isset($params['orgId']) && ($orgId = $request->attributes->get('orgId')))

        $orgId = $request->attributes->get('orgId');
        if ($orgId === 'undefined') {
            throw new Exception("Invalid orgId " . $orgId);
        }

        // Check, if route attributes exists
        if (null === $orgId ) {
            if (!isset($params['orgId'])) {
                return false; // no orgId in the route, so leave.  Could throw an exception.
            }
        }

        // Get actual entity manager for class.  We can also pass it in, but that won't work for the doctrine tree extension.
        $repository = $this->registry->getManagerForClass($configuration->getClass())?->getRepository($configuration->getClass());

        // Try to find the entity
        if (!$org = $repository->findOneBy(['id' => $orgId])) {
            throw new NotFoundHttpException(sprintf('%s %s object not found.', $orgId, $configuration->getClass()));
        }

        // Map found org to the route's parameter
        $request->attributes->set($configuration->getName(), $org);
        return true;
    }

}
