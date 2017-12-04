<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Impression;
use AppBundle\Entity\User;
use AppBundle\Form\UserEmailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
//        $impressionRepo = $this->getDoctrine()->getRepository(Impression::class);
        $userRepo = $this->getDoctrine()->getRepository(User::class);
//        $impressions = $impressionRepo->getLastImpressions(4);


        //todo jeigu useris prisijunges, permeta ji i userprofile
        $user = new User();
        $formUserEmail = $this->createForm(UserEmailType::class, $user, ['attr' => ['data-parsley-validate' => ' ']]);
        $formUserEmail->handleRequest($request);

        if ($formUserEmail->isSubmitted() && $formUserEmail->isValid()) {

            //todo reikia patikrinti ar toks useris dar neegzituoja
            $user->setLoginCount(0);
            $user->setNewsletter(true);
            $userRepo->saveUser($user);
            unset($user);

            return $this->redirectToRoute('app.homepage', [
                //todo atidaro modala ir parodo info, reikia javascriptui perduot
            ]);
        }
        $contentLink = $request->get('content');


        return $this->render('AppBundle:Home:index.html.twig', [
            'formUserEmail' => $formUserEmail->createView(),
            'contentLink' => $contentLink
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
     * @Route("/order", name="app.order.now")
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
