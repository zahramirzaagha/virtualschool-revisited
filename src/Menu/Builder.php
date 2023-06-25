<?php

namespace App\Menu;

use App\Entity\Role;
use App\Entity\User;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class Builder
{
    private FactoryInterface $factory;
    private Security $security;

    public function __construct(FactoryInterface $factory, Security $security)
    {
        $this->factory = $factory;
        $this->security = $security;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $user = $this->security->getUser();
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', ['route' => 'app_home']);
        $menu->addChild('Course', ['route' => 'app_course_index']);
        if ($user && $user->hasRole(Role::Teacher->value)) {
            $menu['Course']->addChild('Teaching', [
                'route' => 'app_course_list_by_instructor',
                'routeParameters' => ['instructorId' => $this->security->getUser()->getId()]
            ]);
        }

        return $menu;
    }
}