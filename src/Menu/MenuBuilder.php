<?php

// src/Menu/MenuBuilder.php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Survos\BaseBundle\Event\KnpMenuEvent;

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
//        $menu = $this->factory->createItem('root');

        $menu = $this->factory->createItem('menuroot',
            [
                'label' => "Menu Root",
                'first' => 'FIRSTCLASS',
                'currentClass' => 'text-danger current-class active show',
                'ancestorClass' => 'text-warning ancestor-class active show',

                'attributes' => [
                    'class' => 'nav nav-sidebar flex-column menuroot-attributes-class',
                ],
// @todo: pass these, so they an depend on what theme is being used.
                'listAttributes' => [
                    'class' => 'nav nav-treeviewXX listAttributes-class'
                ],
                'childrenAttributes' => [
                    'class' => 'nav-link nav-treeview',
                    'data-widget' => 'treeview',
                    'data-accordion' => 'false',
                    'role' => 'menu'
                ],
            ]);

        $childOptions = [
            'attributes' => ['class' => 'nav-treeview'],
            'childrenAttributes' => ['class' => 'list-unstyled nav-treeview show menu-open branch'],
            'labelAttributes' => ['safe_html' => true, 'data-bs-toggle' => 'collapse'],
        ];

//        $this->eventDispatcher->dispatch(new KnpMenuEvent($menu, $this->factory, $options, $childOptions),
//            self::SIDEBAR_MENU_EVENT);


        $menu->addChild('Home', ['route' => 'app_homepage']);
        $bookingMenu = $menu->addChild('booking', ['label' => "Booking"]);
        $bookingMenu->addChild('casa', ['route' => 'booking_index']);
        // ... add more children

        return $menu;
    }
}
