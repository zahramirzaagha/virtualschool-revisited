<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\LocaleSwitcher;

#[Route('/{_locale}')]
class AppController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/switch-language', name: 'app_switch_language')]
    public function switchLanguage(Request $request, LocaleSwitcher $localeSwitcher, RouterInterface $router): Response
    {
        $newLocale = $localeSwitcher->getLocale() == 'en' ? 'fr' : 'en';
        $referer = $request->headers->get('referer');
        if (!$referer)
            return new Response();
        $refererPathInfo = Request::create($referer)->getPathInfo();
        $routeInfos = $router->match($refererPathInfo);
        $refererRoute = $routeInfos['_route'] ?? '';
        $refererParameters = array_merge($routeInfos, ['_locale' => $newLocale]);

        $localeSwitcher->setLocale($newLocale);
        return $this->redirectToRoute($refererRoute, $refererParameters);
    }
}
