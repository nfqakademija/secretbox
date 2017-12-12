<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Impression;
use AppBundle\Service\GeolocationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class HomeController
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="app.homepage")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request, Session $session)
    {
        $contentLink = $request->get('content');
        if(!$contentLink){
            $contentLink = $session->get('content');
        }
        $session->set('content', '');

        $parcelMachines = $this->get(GeolocationService::class)->getParcelMachines($request->getLocale());

//        var_dump($contentLink);die;
        $impressions = $this->getDoctrine()->getRepository(Impression::class)->getLastImpressions(4);

        return $this->render(
            'AppBundle:Home:index.html.twig',
            [
            'contentLink' => $contentLink,
            'impressions' => $impressions,
            'parcelMachines' => $parcelMachines,
            ]
        );
    }

    /**
     * @Route("/loginCheck", name="app.order.now")
     *
     * @param Request $request
     * @param Session $session
     *
     * @return RedirectResponse
     */
    public function orderNowAction(Request $request, Session $session)
    {
        $session->set('content', $request->get('content'));
//        var_dump($content);die;

        return $this->redirectToRoute('app.login.facebook');

//        if ($this->getUser()) {
//
//            return $this->redirectToRoute('app.order.new');
//        } else {
//            $session->set('routeFrom', $request->get('_route'));
//
//            return $this->redirectToRoute('app.login.facebook');
//        }
    }

    /**
     * @Route("/changeLocale/{locale}", name="app.change.locale")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function changeLocaleAction($locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);

        return $this->redirectToRoute('app.homepage');
    }

    /**
     * @Route("/test")
     */
    public function testAction()
    {
        $impression = $this->getDoctrine()->getRepository(Impression::class)->getLastImpressions(4);
        var_dump($impression);
        return new Response('test');
    }
}
//todo acc prisijungus rodo ne emaila
