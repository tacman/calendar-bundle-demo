<?php

namespace App\Menu;

use Survos\BaseBundle\Menu\AdminMenuTrait;
use Umbrella\AdminBundle\Menu\BaseAdminMenu;
use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;
use Umbrella\CoreBundle\Menu\DTO\Breadcrumb;
use Umbrella\CoreBundle\Menu\DTO\Menu;

class AdminMenu extends BaseAdminMenu
{
    /**
     * {@inheritDoc}
     */
    public function buildMenu(MenuBuilder $builder, array $options)
    {
        $root = $builder->root();

//        $this->addMenuItem($root, ['route' => 'org_index']);
//        $this->addMenuItem($root, ['route' => 'org_browse']);
//        $this->addMenuItem($root, ['route' => 'org_new']);
//

        // Create a new entry with route
        $root->add('home')
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

        $root->add('feed')->label('iCal Feeds')
            ->add('List')->route('feed_index')->label('Browse')->end()
            ->add('new')->route('feed_new')->label('New Feed')->end()
//            ->add('view')->route('feed_calendar')->end()
            ;

        // Create a nested entry
        $root->add('Stimulus')
            ->icon('uil-apps')
            ->route('app_stimulus')
            ->end()

        ;

        $root->add('Menu')
            ->icon('uil-apps')
            ->route('app_menu')
            ->end()
        ;

    }

}
