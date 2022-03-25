<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
        ]);
    }

    #[Route('/stimulus', name: 'app_stimulus')]
    public function stimulus(): Response
    {
        return $this->render('app/stimulus.html.twig', [
        ]);
    }

    #[Route('/menu', name: 'app_menu')]
    public function menu(): Response
    {
        return $this->render('app/mmenu_light.html.twig', [
        ]);
    }

}
