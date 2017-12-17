<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\User;
use AppBundle\Service\GeolocationService;
use AppBundle\Service\OrderErrorsMessagesService;
use AppBundle\Service\OrderPriceService;
use AppBundle\Service\ParcelMachine;
use AppBundle\Service\ProductSelectionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class OrderController
 *
 * @package AppBundle\Controller
 *
 * @Route("/order")
 */
class OrderController extends Controller
{
    /**
     * @Route("/new", name="app.order.new")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function newOrderAction(Request $request)
    {
        $deliveryType = $request->get('deliveryType');
        $boxSize = $request->get('secretBoxSize');
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $email = $request->get('email');
        /** @var User $user */
        $user = $this->getUser();

        $suitableProduct = $this->get(ProductSelectionService::class)->selectProperProduct($user->getId(), $boxSize);
        $price = $this->get(OrderPriceService::class)->getCurrentPrice($boxSize);
        if ($suitableProduct == null) {
            return $this->redirectToRoute('app.homepage');
        }

        $address = $deliveryType == "parcel_machine" ? $request->get('parcelMachine') : $request->get('address');

        $order = (new Order())
            ->setDeliveryType($deliveryType)
            ->setUser($user)
            ->setProduct($suitableProduct)
            ->setSellingPrice(number_format($price, 3))
            ->setBoxSize($boxSize)
            ->setDeliveryAddress($address);

        $user
            ->setPhoneNo($request->get('phoneNo'))
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setAddress($address);

        $validator = $this->get('validator');
        $orderErrors = $this->get(OrderErrorsMessagesService::class)->getErrorsList($validator->validate($order));
        $userErrors = $this->get(OrderErrorsMessagesService::class)->getErrorsList($validator->validate($user));

        $errors = array_merge($orderErrors, $userErrors);

        if (count($errors) > 0) {
            return $this->forward('AppBundle:Home:index', [
                'errors' => $errors,
                'content' => 'section-begin-adventure'
            ]);
        } else {
            $this->getDoctrine()->getManager()->getRepository(Order::class)->saveOrder($order);
            $this->getDoctrine()->getManager()->getRepository(User::class)->saveUser($user);

            return $this->redirectToRoute('app.order.payment');
        }
    }

    /**
     * @Route("/locations", name="app.order.locations")
     *
     * @param Request $request
     * @param Session $session
     *
     * @return JsonResponse|RedirectResponse
     */
    public function locationsAction(Request $request, Session $session)
    {
        if ($request->isXmlHttpRequest()) {
            $customerCoordinateX = $request->get('coordinateX');
            $customerCoordinateY = $request->get('coordinateY');
            $customerAddress = $request->get('address');
        } else {
//
            return $this->redirectToRoute('app.homepage');
        }

        $parcelMachines = $session->get('parcelMachines');
        $parcelMachines = $this->get(GeolocationService::class)->addDistanceToMachines(
            $parcelMachines,
            $customerCoordinateX,
            $customerCoordinateY,
            $customerAddress
        );

        if (empty($parcelMachines)) {
            return new JsonResponse(
                [
                    'status' => 0,
                ],
                200
            );
        }

        $machinesArray = [];
        /**
         * @var ParcelMachine $machine
        */
        foreach ($parcelMachines as $machine) {
            array_push($machinesArray, $machine->getMachineArray());
        }
        usort(
            $machinesArray,
            function ($a, $b) {
                return $a['distanceValue'] <=> $b['distanceValue'];
            }
        );

        return new JsonResponse(
            [
            'status' => 1,
            'parcelMachines' =>  $machinesArray
            ],
            200
        );
    }

    /**
     * @Route("/payment", name="app.order.payment")
     *
     * @return Response
     */
    public function paymentAction()
    {
        return $this->render('@App/Order/payment.html.twig');
    }
}
