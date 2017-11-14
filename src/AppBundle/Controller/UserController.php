<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Impression;
use AppBundle\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UserController
 *
 * @package AppBundle\Controller
 *
 * @Route("/{_locale}/user", defaults={"_locale": "lt"}, requirements={"_locale" = "%app.locales%"})
 */
class UserController extends Controller
{
    /**
     * @Route("/profile", name="app.user.profile")
     */
    public function userPageAction()
    {
        $orderRepo = $this->getDoctrine()->getManager()->getRepository(Order::class);
        $impressionRepo = $this->getDoctrine()->getManager()->getRepository(Impression::class);

        $user = $this->getUser();
        $userOrders = $orderRepo->getUserRevealedOrders($user->getId());
        $userSecrets = $orderRepo->getUserSecrets($user->getId());
        $userImpressions = $impressionRepo->getImpressions($user->getId());

        return $this->render('AppBundle:User:profile.html.twig', [
            'orders' => $userOrders,
            'secrets' => $userSecrets,
            'impressions' => $userImpressions,
        ]);
    }
}
