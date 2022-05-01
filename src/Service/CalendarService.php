<?php

namespace App\Service;

use App\Entity\Booking;
use App\Entity\Feed;
use Carbon\Carbon;
use ICal\ICal;
use IcsReader\IcsReader;
use League\Csv\Reader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CalendarService
{
    public function __construct(
        private CacheInterface $cache,
        private HttpClientInterface $client,
        private ParameterBagInterface $bag) {

    }
    public function loadCsv()
    {
        $path = $this->bag->get('kernel.project_dir') . '/event_information_data.csv';
        assert(file_exists($path));

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // use the first line as headers for rows

        $header = $csv->getHeader();

        $rows = $csv->getRecords();
        return $rows;
        foreach ($rows as $row) {
            dd($row);
            var_dump($row);
        }
    }

    public function parseUsingIcal(Feed $feed): Feed
    {
            $ical = new ICal($feed->getUrl(), array(
                'defaultSpan'                 => 2,     // Default value
                'defaultTimeZone'             => 'UTC',
                'defaultWeekStart'            => 'MO',  // Default value
                'disableCharacterReplacement' => false, // Default value
                'filterDaysAfter'             => null,  // Default value
                'filterDaysBefore'            => null,  // Default value
                'skipRecurrence'              => false, // Default value
            ));
            // $ical->initFile('ICal.ics');
            // $ical->initUrl('https://raw.githubusercontent.com/u01jmg3/ics-parser/master/examples/ICal.ics', $username = null, $password = null, $userAgent = null);
        try {
        } catch (\Exception $e) {
            die($e);
        }
        foreach ($ical->events() as $icalEvent) {
            $booking = (new Booking())
                ->setTitle($icalEvent->summary)
                ->setBeginAt(Carbon::parse($icalEvent->dtstart))
            ;
//            dd($icalEvent, $booking);
            $feed->addBooking($booking);
        }
        return $feed;

    }

    public function parseIcs($icsContent)
    {
        $reader = new IcsReader();
        $ics = $reader->parse($icsContent);

        dump($ics->getCalendar());
        dd($ics->getEvents());
        return $ics;

    }

    public function loadByUrl(string $url) {
        return $this->parseIcs($this->fetchUrl($url));
    }
    public function fetchUrl(string $url): string {

    $content = $this->cache->get($key = md5($url), function(ItemInterface $item) use ($url) {
        $item->expiresAfter(3600);
        return $this->client->request('GET', $url)->getContent();
        });
        return $content;
    }
}
