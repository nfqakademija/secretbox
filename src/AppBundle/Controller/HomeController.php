<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Impression;
use AppBundle\Service\EventsAndCustomersCountService;
use AppBundle\Service\GeolocationService;
use AppBundle\Service\OrderPriceService;
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
     * @param Session $session
     * @param array $errors
     * @param string $content
     *
     * @return Response
     */
    public function indexAction(Request $request, Session $session, $errors = [], $content = "")
    {
        if (strlen($content) > 0) {
            $contentLink = $content;
        } else {
            $contentLink = $request->get('content');
        }
        $session->set('content', '');

        $parcelMachines = $this->get(GeolocationService::class)->getParcelMachines($request->getLocale());
        $session->set('parcelMachines', $parcelMachines);
        $prices = $this->get(OrderPriceService::class)->getAllPrices();
        $eventsAndCustomers = $this->get(EventsAndCustomersCountService::class)->getEventsAndCustomers();
        $impressions = $this->getDoctrine()->getRepository(Impression::class)->getLastImpressions(4);
        $user = $this->getUser();

        return $this->render(
            'AppBundle:Home:index.html.twig',
            [
            'contentLink' => $contentLink,
            'impressions' => $impressions,
            'parcelMachines' => $parcelMachines,
            'eventsAndCustomers' => $eventsAndCustomers,
            'prices' => $prices,
            'user' => $user,
            'errors' => $errors
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

        return $this->redirectToRoute('app.login.facebook');
    }

    /**
     * @Route("/changeLocale/{locale}", name="app.change.locale")
     *
     * @param string $locale
     * @param Request $request
     *
     * @return Response
     */
    public function changeLocaleAction($locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);

        return $this->redirectToRoute('app.homepage');
    }
}
