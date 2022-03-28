<?php

// src/Menu/MenuBuilder.php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    private $factory;

    /**
     * Add any other dependency you need...
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', ['route' => 'app_homepage']);
        $bookingMenu = $menu->addChild('booking', ['label' => "Booking"]);
        $bookingMenu->addChild('casa', ['route' => 'booking_index']);
        // ... add more children

        return $menu;
    }
}
