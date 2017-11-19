<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Impression;
use AppBundle\Entity\Order;
use AppBundle\Form\ImpressionType;
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

        $user = $this->getUser();
        $userOrders = $orderRepo->getUserRevealedOrders($user->getId());
        $userSecrets = $orderRepo->getUserSecrets($user->getId());


        $impression = new Impression();
        $form = $this->createForm(ImpressionType::class, $impression, ['attr' => ['data-parsley-validate' => ' ']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $impression->setUserId($user->getId());
            $impressionRepo->saveImpression($impression);
            unset($impression);
            return $this->redirectToRoute('app.user.profile');
//            return $this->render()
        }

        $userImpressions = $impressionRepo->getImpressions($user->getId());

        return $this->render('AppBundle:User:profile.html.twig', [
            'orders' => $userOrders,
            'secrets' => $userSecrets,
            'impressions' => $userImpressions,
            'form' => $form->createView()
        ]);
    }
}
