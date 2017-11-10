<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @Route("/{_locale}", defaults={"_locale": "lt"}, requirements={"_locale" = "%app.locales%"})
 *
 */
class HomeController extends Controller
{

    /**
     * @Route("/", name="app.homepage")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Home:index.html.twig', [

        ]);
    }

    /**
     * @Route("/about", name="app.about")
     */
    public function aboutAction()
    {
        return $this->render('AppBundle:Home:about.html.twig');
    }

    /**
     *
     * @Route("/order", name="app.order.now")
     */
    public function orderNowAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app.order.new');
        } else {
            return $this->redirectToRoute('app_facebook_connect');
        }
    }
}
