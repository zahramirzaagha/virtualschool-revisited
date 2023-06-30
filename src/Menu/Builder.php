<?php

namespace App\Menu;

use App\Entity\Role;
use App\Entity\User;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;

final class Builder
{
    private FactoryInterface $factory;
    private Security $security;
    private TranslatorInterface $translator;
    private LocaleSwitcher $localeSwitcher;

    public function __construct(FactoryInterface $factory, Security $security, TranslatorInterface $translator, LocaleSwitcher $localeSwitcher)
    {
        $this->factory = $factory;
        $this->security = $security;
        $this->translator = $translator;
        $this->localeSwitcher = $localeSwitcher;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $user = $this->security->getUser();
        $menu = $this->factory->createItem('root');

        $menu->addChild($this->translator->trans('menu_home'), ['route' => 'app_home']);
        if ($user && !$user->hasRole(Role::Teacher->value))
        {
            $menu->addChild($this->translator->trans('menu_courses'), ['route' => 'app_course_index']);
        }
        if ($user && $user->hasRole(Role::Teacher->value))
        {
            $menu->addChild($this->translator->trans('menu_courses_i_teach'), [
                'route' => 'app_course_index_by_instructor',
                'routeParameters' => ['instructorId' => $this->security->getUser()->getId()]
            ]);
        }
        $menu->addChild($this->translator->trans('menu_statistics'), ['route' => 'app_stats']);
        if ($user)
        {
            $menu->addChild($this->translator->trans('menu_logout'), ['route' => 'app_logout']);
        }
        else
        {
            $menu->addChild($this->translator->trans('menu_register'), ['route' => 'app_register']);
            $menu->addChild($this->translator->trans('menu_login'), ['route' => 'app_login']);
        }
        if ($this->localeSwitcher->getLocale() == 'en')
            $menu->addChild($this->translator->trans('menu_switch_french'), ['route' => 'app_switch_language']);
        else
            $menu->addChild($this->translator->trans('menu_switch_english'), ['route' => 'app_switch_language']);

        return $menu;
    }
}