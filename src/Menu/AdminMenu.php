<?php

namespace App\Menu;

use Survos\BaseBundle\Menu\AdminMenuTrait;
use Umbrella\AdminBundle\Menu\BaseAdminMenu;
use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;
use Umbrella\CoreBundle\Menu\DTO\Breadcrumb;
use Umbrella\CoreBundle\Menu\DTO\Menu;

class AdminMenu extends BaseAdminMenu
{
    use AdminMenuTrait;
    /**
     * {@inheritDoc}
     */
    public function buildMenu(MenuBuilder $builder, array $options)
    {
        $root = $builder->root();

        $this->addMenuItem($root, ['route' => 'booking_index']);
        $this->addMenuItem($root, ['route' => 'feed_index']);
        $this->addMenuItem($root, ['route' => 'feed_new']);



        // Create a new entry with route
        $root->add('welcome')
            ->icon('uil-home') // Icon of entry
            ->route('app_homepage'); // Route of entry

//        // Create a new entry with url
//        $root->add('google')
//            ->icon('mdi mdi-google') // Icon of entry
//            ->route('app_welcome'); // Url of entry

        // Create a nested entry
        $root->add('Booking')
            ->icon('uil-apps')
            ->add('List')
            ->route('booking_index')
            ->end()
            ->add('new')->route('booking_new')->end()
            ->add('view')->route('booking_calendar')->end()

        ;

        // Create a nested entry
        $root->add('Stimulus')
            ->icon('uil-apps')
            ->route('app_stimulus')
            ->end()

        ;

        // Create a nested entry
        $root->add('Menu')
            ->icon('uil-apps')
            ->route('app_menu')
            ->end()

        ;

    }

}
