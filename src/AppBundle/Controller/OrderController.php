<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use AppBundle\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OrderController
 * @package AppBundle\Controller
 * @Route("/{_locale}/order", defaults={"_locale": "lt"}, requirements={"_locale" = "%app.locales%"})
 */
class OrderController extends Controller
{
    /**
     * @Route("/new", name="app.order.new")
     */
    public function newOrderAction(Request $request)
    {
        $orderService = $this->get(OrderService::class);
        $order = new Order();
        $user = $this->getUser();
        $order->setDeliveryAddress($user->getAddress());
        $form = $this->createForm(OrderType::class, $order, array('attr' => array('data-parsley-validate' => ' ')));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setUserId($user->getId());
            $order->setSellingPrice(19.99);
            //todo EventListener kad productId = random is products, kuris nera buves pas useri
            $order->setProductId(1);
            $orderService->createNewOrder($order);

            return $this->redirectToRoute('app.user.profile');
        }

        return $this->render('AppBundle:Order:new.order.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
