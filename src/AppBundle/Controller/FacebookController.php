<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class FacebookController extends Controller
{
    /**
     * @Route("/connect/facebook")
     */
    public function connectAction()
    {
        return $this->get('oauth2.registry')
            ->getClient('facebook_main')
            ->redirect();
    }

    /**
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction(Request $request)
    {
        //todo redirect to new order page if button clicked
        return $this->redirectToRoute('app.homepage');
    }


    /**
     * @Route("/")
     */
    public function noRoute()
    {
        return $this->redirectToRoute('app.homepage');
    }
}
