<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class HomeController
 *
 * @Route("/{_locale}", defaults={"_locale": "lt"}, requirements={"_locale" = "%app.locales%"})
 *
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
    public function indexAction(Request $request)
    {
        $contentLink = $request->get('content');

        return $this->render('AppBundle:Home:index.html.twig', [
            'contentLink' => $contentLink
        ]);
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
        if ($this->getUser()) {
            return $this->redirectToRoute('app.order.new');
        } else {
            $session->set('routeFrom', $request->get('_route'));

            return $this->redirectToRoute('app.login.facebook');
        }
    }
}
