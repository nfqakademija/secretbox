<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Impression;
use AppBundle\Entity\Order;
use AppBundle\Entity\User;
use AppBundle\Form\ImpressionType;
use AppBundle\Form\UserAddressType;
use AppBundle\Service\FacebookInfoService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function userPageAction(Request $request)
    {
        $orderRepo = $this->getDoctrine()->getManager()->getRepository(Order::class);
        $impressionRepo = $this->getDoctrine()->getManager()->getRepository(Impression::class);
        $userRepo = $this->getDoctrine()->getManager()->getRepository(User::class);

        $user = $this->getUser();
        $userOrders = $orderRepo->getUserRevealedOrders($user->getId());
        $userSecrets = $orderRepo->getUserSecrets($user->getId());

        $impression = new Impression();
        $formImpression = $this->createForm(ImpressionType::class, $impression, ['attr' => ['data-parsley-validate' => ' ']]);
        $formAddress = $this->createForm(UserAddressType::class, $user, ['attr' => ['data-parsley-validate' => ' ']]);
        $formImpression->handleRequest($request);
        $formAddress->handleRequest($request);

        if ($formImpression->isSubmitted() && $formImpression->isValid()) {
            $impression->setUser($user);
            $impressionRepo->saveImpression($impression);

            return $this->redirectToRoute('app.user.profile');
        }

        if ($formAddress->isSubmitted() && $formAddress->isValid()) {
            $userRepo->saveUser($user);

            return $this->redirectToRoute('app.user.profile');
        }

        $userImpressions = $user->getImpressions();


        $friendList = $this->get(FacebookInfoService::class)->getUserDataByReference('friends?fields=id,name,picture');

        return $this->render('AppBundle:User:profile.html.twig', [
            'user' => $user,
            'orders' => $userOrders,
            'secrets' => $userSecrets,
            'impressions' => $userImpressions,
            'formImpression' => $formImpression->createView(),
            'formAddress' => $formAddress->createView(),
            'friends' => $friendList,
            'isUserProfile' => true
        ]);
    }

    /**
     * @Route("/profile/subscribe", name="app.user.subscription")
     */
    public function newImpression()
    {
        $user = $this->getUser();
        $userRepo = $this->getDoctrine()->getManager()->getRepository(User::class);

        if ($user->getNewsletter()) {
            $user->setNewsletter(false);
        } else {
            $user->setNewsletter(true);
        }

        $userRepo->saveUser($user);

        return $this->redirectToRoute('app.user.profile');
    }

    /**
     * @Route("/friend/{facebookId}", defaults={"facebookId"=0}, name="app.user.friend")
     */
    public function getUserFriendAction($facebookId)
    {
        $orderRepo = $this->getDoctrine()->getManager()->getRepository(Order::class);
//        $impressionRepo = $this->getDoctrine()->getManager()->getRepository(Impression::class);
        $userRepo = $this->getDoctrine()->getManager()->getRepository(User::class);

//        $user = $this->getUser();

        $isMyFriend = $this->get(FacebookInfoService::class)->isMyFriend($facebookId);
        if (!$isMyFriend) {
            return $this->redirectToRoute('app.user.profile');
        }

        $user = $userRepo->findOneBy(['facebookId' => $facebookId]);

        $userOrders = $orderRepo->getUserRevealedOrders($user->getId());
        $userSecrets = $orderRepo->getUserSecrets($user->getId());
        $userImpressions = $user->getImpressions();

        return $this->render('AppBundle:User:profile.html.twig', [
            'orders' => $userOrders,
            'secrets' => $userSecrets,
            'impressions' => $userImpressions,
            'user' => $user,
            'isUserProfile' => false
        ]);
    }
}
