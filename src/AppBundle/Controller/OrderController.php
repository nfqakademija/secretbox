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
//    /**
//     * @Route("/new2/{friendId}", defaults={"friendId"=null}, name="app2.order.new")
//     *
//     * @param integer $friendId
//     * @param Request $request
//     * @param Session $session
//     *
//     * @return Response
//     */
//    public function new2OrderAction($friendId, Request $request, Session $session)
//    {
//        //todo perskaityt clean code knygute uncle bob
//        //todo        tiekejus i atskira lenetele
//        //todo overflow auto, skrilinamas tekstas
//        //todo pinterest
//        //todo dizaino variantai: graphic river
//        //todo svg iconos
//        //todo html image map
//        //todo  frontende: pabandyk pats, draugams, giftas?
//        //todo paaiskint ka gaus
//        $geoLocationService = $this->get(GeolocationService::class);
//        $productSelectionService = $this->get(ProductSelectionService::class);
//
//        $orderRepo = $this->getDoctrine()->getManager()->getRepository(Order::class);
//
//        $locale = $request->getLocale();
//        $parcelMachines = $this->get(GeolocationService::class)->getParcelMachines($locale);
//        $session->set('parcelMachines', $parcelMachines);
//
//        $parcelMachineNames = $geoLocationService->getOnlyNames($locale);
//
//        $order = new Order();
//
//
//        if ($friendId == null) {
//            $user = $this->getUser();
//            $isUserOrder = true;
//        } else {
//            $userRepo = $this->getDoctrine()->getManager()->getRepository(User::class);
//            $user = $userRepo->findOneBy(['facebookId' => $friendId]);
//            $isUserOrder = false;
//        }
//
//        //todo kai paspaudzia pradeti nuotyki mygtuka, divas per visa ekrana: ieskomas nuotykis
//        $suitableProduct = $productSelectionService->selectProperProduct($user->getId());
//        if ($suitableProduct == null) {
//            return $this->redirectToRoute('app.order.no.orders');
//        }
//
//        $order->setDeliveryAddress($user->getAddress());
//        $form = $this->createForm(
//            OrderType::class,
//            $order,
//            [
//            'attr' => ['data-parsley-validate' => ' '],
//            'parcelMachines' => $parcelMachineNames,
//            ]
//        );
//        $form->handleRequest($request);
//        //todo kitas action
//        if ($form->isSubmitted() && $form->isValid()) {
//            $order->setUser($user);
//
//            $price = $this->getDoctrine()->getRepository('AppBundle:Price')->getCurrentPrice();
//
//            $order->setSellingPrice($price->getPrice());
//            $order->setProduct($suitableProduct);
//
//            $validator = $this->get('validator');
//            $errors = $validator->validate($order);
//
//            if (count($errors) > 0) {
//                return $this->render(
//                    'AppBundle:Order:new.order.html.twig',
//                    [
//                    'errors' => $errors
//                    ]
//                );
//            }
//
//            $orderRepo->saveOrder($order);
//
//            //todo jeigu uzsakymas draugui - grazinti i draugo profili
//            return $this->redirectToRoute('app.user.profile');
//        }
//
//        return $this->render(
//            'AppBundle:Order:new.order.html.twig',
//            [
//            'form' => $form->createView(),
//            'parcelMachines' => $parcelMachines,
//            'user' => $user,
//            'isUserOrder' => $isUserOrder,
//            ]
//        );
//    }

    /**
     * @Route("/new", name="app.order.new")
     */
    public function newOrderAction(Session $session, Request $request)
    {

        /** @var User $user */
        //todo produkta parenka ir pagal box size
        $deliveryType = $request->get('deliveryType');
        $boxSize = $request->get('secretBoxSize');
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $email = $request->get('email');

//        $me = $this->getUser();
//        if(!$userId){
//            $userId = $this->getUser()->getId();
//        }

        $userId = $session->get('orderUserId');
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($userId);
//        var_dump($user);die;


        $suitableProduct = $this->get(ProductSelectionService::class)->selectProperProduct($user->getId());
        $price = $this->get(OrderPriceService::class)->getCurrentPrice($boxSize);


        $address = $deliveryType == "parcel_machine" ? $request->get('parcelMachine') : $request->get('address');

        $order = (new Order())
            ->setDeliveryType($deliveryType)
            ->setUser($user)
            ->setProduct($suitableProduct)
            ->setSellingPrice(number_format($price, 3))
            ->setDeliveryAddress($address);

        $user
            ->setPhoneNo($request->get('phoneNo'))
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setAddress($address);

//        var_dump( $_POST, $order);die;
        //todo USERIU ROLES pakeist i ENUM
        //todo errorus sujungt ir rodyt jei tokiu yra
        $validator = $this->get('validator');
        $orderErrors = $this->get(OrderErrorsMessagesService::class)->getErrorsList($validator->validate($order));
        $userErrors = $this->get(OrderErrorsMessagesService::class)->getErrorsList($validator->validate($user));;
        $errors = array_merge($orderErrors,  $userErrors);
//        var_dump($errors);die;

        if(count($errors) > 0){
//            var_dump($errors);die;

            return $this->forward('AppBundle:Home:index',[
                'errors' => $errors,
                'content' => 'section-begin-adventure',
                'user' => $user,

            ]);
        } else {
            $this->getDoctrine()->getManager()->getRepository(Order::class)->saveOrder($order);
            $this->getDoctrine()->getManager()->getRepository(User::class)->saveUser($user);

            return $this->redirectToRoute('app.order.payment');
        }
        //todo JS validacijos


    }

    /**
     * @Route("/allDone", name="app.order.no.orders")
     *
     * @return Response
     */
    public function allDoneAction()
    {
        return $this->render('@App/Order/no.orders.html.twig');
    }

    /**
     * @Route("/locations", name="app.order.locations")
     */
    public function locationsAction(Request $request, Session $session)
    {
        if ($request->isXmlHttpRequest()) {
            $itsXML = 'yes, its XML';
            $customerCoordinateX = $request->get('coordinateX');
            $customerCoordinateY = $request->get('coordinateY');
            $customerAddress = $request->get('address');
        } else {
//            http://localhost:8000/order/locations?coordinateX=54.923094799999994&coordinateY=23.8207859&address=false
            ////            http://localhost:8000/order/locations?data=54.923111723.8207956&coordinateX=54.9231117&coordinateY=23.8207956&address=false&coordinates=iksas+ir+ygrikas


            $itsXML = 'i will return you to NEW ORDER!!!!!';
            $customerCoordinateX = "";
//            $customerCoordinateX = 54.923094799999994;
            $customerCoordinateY = "";
//            $customerCoordinateY = 23.8207859;
//            $customerAddress = "Draugystės g. 11, Laičiai, Ukmergės raj.";
            $customerAddress = "Medvegalio 9, Kaunas";
//            $customerAddress = "Saltoniškių prospektas 85-98, Panevėžys";
//            $customerAddress = "";
        }

        $parcelMachines = $session->get('parcelMachines');
//        var_dump($parcelMachines);die;





        $parcelMachines = $this->get(GeolocationService::class)->addDistanceToMachines(
            $parcelMachines,
            $customerCoordinateX,
            $customerCoordinateY,
            $customerAddress
        );

        if(empty($parcelMachines)){
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
//            'coordinates' => $request->get('coordinateX') . ' AND ' . $request->get('coordinateY') . $itsXML,
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
