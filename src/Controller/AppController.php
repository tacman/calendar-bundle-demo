<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Jsvrcek\ICS\CalendarExport;
use Jsvrcek\ICS\Utility\Formatter;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    public function __construct(private Formatter $formatter, private CalendarExport $calendarExport) {

    }

    #[Route('/ics.ics', name: 'app_calendar_ics', methods: ['POST','GET'])]
    public function ics(Request $request, EventRepository $eventRepository): Response
    {

        $calendar = Calendar::create('Test Calendar');

        $event = Event::create()
            ->name('Laracon Online')
            ->description('Experience Laracon all around the world')
            ->uniqueIdentifier('A unique identifier can be set here')
            ->createdAt(new \DateTimeImmutable())
            ->startsAt(new \DateTimeImmutable())
            ->endsAt(new \DateTimeImmutable("+ 1hour"));

        $ics = $calendar
            ->event($event)
            ->get();
//        dd($ics);
        return new Response($ics, 200, ['Content-Type'=> 'text/calendar']);
    }


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
