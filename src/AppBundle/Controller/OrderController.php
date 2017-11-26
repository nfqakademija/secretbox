<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use AppBundle\Form\OrderType;
use AppBundle\Service\ProductSelectionService;
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
        $productSelectionService = $this->get(ProductSelectionService::class);

        $orderRepo = $this->getDoctrine()->getManager()->getRepository(Order::class);
        //todo pakeisti, kad slectProperproduct grazintu ne ID o visa Product objekta
        $productRepo = $this->getDoctrine()->getManager()->getRepository(Product::class);

        $order = new Order();
        $user = $this->getUser();
        $order->setDeliveryAddress($user->getAddress());
        $form = $this->createForm(OrderType::class, $order, ['attr' => ['data-parsley-validate' => ' ']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setUser($user);
            $order->setSellingPrice(19.99);
            $suitableProductId = $productSelectionService->selectProperProduct($user->getId());
            $suitableProduct = $productRepo->findOneBy(['id' => $suitableProductId]);

            $order->setProduct($suitableProduct);

            $validator = $this->get('validator');
            $errors = $validator->validate($order);

            if (count($errors) > 0) {
                return $this->render('AppBundle:Order:new.order.html.twig', [
                    'errors' => $errors
                ]);
            }

            $orderRepo->saveOrder($order);

            return $this->redirectToRoute('app.user.profile');
        }

        return $this->render('AppBundle:Order:new.order.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/begin", name="app.order.begin")
     *
     * @return Response
     */
    public function beginOrderAction()
    {
        return $this->render('AppBundle:Order:begin.order.html.twig');
    }
}
