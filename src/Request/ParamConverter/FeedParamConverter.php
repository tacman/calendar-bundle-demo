<?php
declare(strict_types=1);

namespace App\Request\ParamConverter;

use App\Entity\Feed;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FeedParamConverter implements ParamConverterInterface
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
        return Feed::class == $configuration->getClass();
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

//        if (isset($params['feedId']) && ($feedId = $request->attributes->get('feedId')))

        $feedId = $request->attributes->get('feedId');
        if ($feedId === 'undefined') {
            throw new Exception("Invalid feedId " . $feedId);
        }

        // Check, if route attributes exists
        if (null === $feedId ) {
            if (!isset($params['feedId'])) {
                return false; // no feedId in the route, so leave.  Could throw an exception.
            }
        }

        // Get actual entity manager for class.  We can also pass it in, but that won't work for the doctrine tree extension.
        $repository = $this->registry->getManagerForClass($configuration->getClass())?->getRepository($configuration->getClass());

        // Try to find the entity
        if (!$feed = $repository->findOneBy(['id' => $feedId])) {
            throw new NotFoundHttpException(sprintf('%s %s object not found.', $feedId, $configuration->getClass()));
        }

        // Map found feed to the route's parameter
        $request->attributes->set($configuration->getName(), $feed);
        return true;
    }

}
