<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class FacebookController extends Controller
{
    /**
     * @Route("/connect/facebook", name="app.login.facebook")
     */
    public function connectAction()
    {
//        $session->set('routeFrom', $request->get('_route'));
        ////        $session->set('pageSection', $pageSection);

        return $this->get('oauth2.registry')
            ->getClient('facebook_main')
            ->redirect();
    }

    /**
     * @Route("/connect/facebook/check", name="app.connect.facebook.check")
     *
     * @param Session $session
     *
     * @return RedirectResponse
     */
    public function connectCheckAction(Session $session)
    {
        $route = $session->get('routeFrom');
        if ($route == "") {
            $route = "app.homepage";
        }

        return $this->redirectToRoute($route);
    }
}
