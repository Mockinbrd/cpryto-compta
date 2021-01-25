<?php

namespace App\Controller;

use App\Service\GetReferer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends AbstractController
{
    /**
     * @Route("/locale", name="change_locale")
     */
    public function index(Request $request, GetReferer $getReferer): Response
    {
        $locale = $request->getLocale();
        $locale === 'fr' ? $locale = 'en' : $locale = 'fr';
        $request->getSession()->set('_locale', $locale);

        return $this->redirect($getReferer->referer($request));
    }
}
