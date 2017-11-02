<?php

namespace AppBundle\Controller;

use GuzzleHttp\Psr7\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * @Route("/user_profile", name="app.user.profile")
     */
    public function userProfile()
    {
        return $this->render('AppBundle:User:user.profile.html.twig');
    }
}
