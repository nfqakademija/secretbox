<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Impression;
use AppBundle\Entity\User;
use AppBundle\Service\EventsAndCustomersCountService;
use AppBundle\Service\FacebookInfoService;
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
//     * @Route("/{test}/{test2}", defaults={"test":"", "test2":""}, name="app.homepage")
     * @Route("/", name="app.homepage")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request, Session $session, $errors = false, $content = false)
    {
        if ($content) {
            $contentLink = $content;
        } else {
            $contentLink = $request->get('content');
//            if (!$contentLink) {
//                $contentLink = $session->get('content');
//            }
        }
        $session->set('content', '');

        $parcelMachines = $this->get(GeolocationService::class)->getParcelMachines($request->getLocale());
        $session->set('parcelMachines', $parcelMachines);

        $prices = $this->get(OrderPriceService::class)->getAllPrices();

//        var_dump($prices);die;

        $eventsAndCustomers = $this->get(EventsAndCustomersCountService::class)->getEventsAndCustomers();
//        $parcelMachineNames = $this->get(GeolocationService::class)->getOnlyNames($request->getLocale());

//        var_dump($contentLink);die;
        $impressions = $this->getDoctrine()->getRepository(Impression::class)->getLastImpressions(4);



        $user = $this->getUser();
//        var_dump($user); die;

//        $session->set('orderUserId', $user->getId());

//        $order = new Order();
//        $orderForm = $this->createForm(
//            OrderType::class,
//            $order,
//            [
//                'attr' => ['data-parsley-validate' => ' '],
//                'parcelMachines' => $parcelMachineNames,
//            ]
//        );


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
            //            'test'=> $test,
            //            'test2'=> $test2
            //            'orderForm' => $orderForm->createView()
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
}
