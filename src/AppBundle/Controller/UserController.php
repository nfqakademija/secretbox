<?php

namespace AppBundle\Controller;


use AppBundle\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/{_locale}/user", defaults={"_locale": "lt"}, requirements={"_locale" = "%app.locales%"})
 */
class UserController extends Controller
{
    /**
     * @Route("/profile", name="app.user.profile")
     */
    public function userPageAction()
    {
        $orderService = $this->get(OrderService::class);
        $user = $this->getUser();
        $userOrders = $orderService->getUserOrders($user->getId());
        $userSecrets = $orderService->getUserSecrets($user->getId());
        return $this->render('AppBundle:User:profile.html.twig', array(
            'orders' => $userOrders,
            'secrets' => $userSecrets,
        ));
    }

}
