<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrderController
 *
 * @package AppBundle\Controller
 *
 * @Route("/{_locale}/order", defaults={"_locale": "lt"}, requirements={"_locale" = "%app.locales%"})
 */
class OrderController extends Controller
{
    /**
     * @Route("/new", name="app.order.new")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newOrderAction(Request $request)
    {
        $orderRepo = $this->getDoctrine()->getManager()->getRepository(Order::class);
        $order = new Order();
        $user = $this->getUser();
        $order->setDeliveryAddress($user->getAddress());
        $form = $this->createForm(OrderType::class, $order, ['attr' => ['data-parsley-validate' => ' ']]);

        $form->handleRequest($request);

        //todo valdidationus

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setUserId($user->getId());
            $order->setSellingPrice(19.99);
            //todo service kad productId = random is products, kuris nera buves pas useri
            $order->setProductId(1);
            $orderRepo->saveOrder($order);

            return $this->redirectToRoute('app.user.profile');
        }

        return $this->render('AppBundle:Order:new.order.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
