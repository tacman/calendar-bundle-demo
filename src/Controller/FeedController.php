<?php

// uses Survos Param Converter, from the UniqueIdentifiers method of the entity.

namespace App\Controller;

use App\Entity\Feed;
use App\Form\FeedType;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Survos\BaseBundle\Traits\WorkflowHelperTrait;
use Survos\WorkflowBundle\Traits\HandleTransitionsTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/feed/{feedId}')]
class FeedController extends AbstractController
{
    use WorkflowHelperTrait;
    use HandleTransitionsTrait;
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route(path: '/feed_transition/{transition}.{_format}', name: 'feed_transition', options: ['expose' => true])]
    public function feed_transition(Request $request, Feed $feed, WorkflowInterface $feedStateMachine, ?string $transition=null, $_format = 'json')
    {
        // if there's no transition, it's because it's part of a form
        if (empty($transition) || $transition == '_') {
            $transition = $request->request->get('transition');
        }
        $jsonResponse = $this->_transition($request, $feed, $transition, $feedStateMachine, $this->entityManager, Feed::class, $_format);
        if ($_format === 'json') {
            return $jsonResponse;
        } else {
            return $this->redirectToRoute('feed_show', $feed->getRP());
        }

    }

    #[Route('/', name: 'feed_show')]
    public function show(Feed $feed): Response
    {
        ray( $feed);
        return $this->render('feed/show.html.twig', [
            'feed' => $feed,
        ]);
    }

    #[Route('/edit', name: 'feed_edit')]
    public function edit(Request $request, Feed $feed): Response
    {
        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('feed_index');
        }

        return $this->render('feed/edit.html.twig', [
            'feed' => $feed,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete', name: 'feed_delete', methods: ['DELETE'])]
    public function delete(Request $request, Feed $feed): Response
    {
        // hard-coded to getId, should be get parameter of uniqueIdentifiers()
        if ($this->isCsrfTokenValid('delete'.$feed->getId(), $request->request->get('_token'))) {
            $entityManager = $this->entityManager;
            $entityManager->remove($feed);
            $entityManager->flush();
        }

        return $this->redirectToRoute('feed_index');
    }
}
