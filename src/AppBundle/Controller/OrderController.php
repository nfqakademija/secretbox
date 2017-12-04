<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Form\OrderType;
use AppBundle\Service\GeolocationService;
use AppBundle\Service\ProductSelectionService;
use function MongoDB\BSON\toJSON;
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
 * @Route("/{_locale}/order", defaults={"_locale": "lt"}, requirements={"_locale" = "%app.locales%"})
 */
class OrderController extends Controller
{
    /**
     * @Route("/new/{friendId}", defaults={"friendId"=null}, name="app.order.new")
     *
     * @param integer $friendId
     * @param Request $request
     *
     * @param Session $session
     *
     * @return Response
     */
    public function newOrderAction($friendId, Request $request, Session $session)
    {
        $geolocationService = $this->get(GeolocationService::class);

        $productSelectionService = $this->get(ProductSelectionService::class);

        $orderRepo = $this->getDoctrine()->getManager()->getRepository(Order::class);
        $productRepo = $this->getDoctrine()->getManager()->getRepository(Product::class);



        $locale = $request->getLocale();
//        $parcelMachines = $this->get(GeolocationService::class)->getDisatance();
        $parcelMachines = $this->get(GeolocationService::class)->getParcelMachines($locale);
        $session->set('parcelMachines', $parcelMachines);

//        $parcelMachineNames = array_column(array_map(create_function('$object', 'return $object->getName();'), $parcelMachines), 'name');
//        var_dump(array_column($parcelMachines, 'name'));die;
        $parcelMachineNames = $geolocationService->getOnlyNames($locale);
        //var_dump($parcelMachineNames);die;
        //var_dump(array_column($parcelMachineNames, 0));die;

        $order = new Order();

//        var_dump($friendId);die;

        if ($friendId == null) {
            $user = $this->getUser();
            $isUserOrder = true;
        } else {
//            var_dump($friendId);die;
            $userRepo = $this->getDoctrine()->getManager()->getRepository(User::class);
            $user = $userRepo->findOneBy(['facebookId' => $friendId]);
            $isUserOrder = false;
        }

//        var_dump($user);die;

        $order->setDeliveryAddress($user->getAddress());
        $form = $this->createForm(OrderType::class, $order, [
            'attr' => ['data-parsley-validate' => ' '],
            'parcelMachines' => $parcelMachineNames,
        ]);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $order->setUser($user);
            $order->setSellingPrice(19.99);
            //todo patikrint ar suitable product ne null
            $suitableProduct = $productSelectionService->selectProperProduct($user->getId());
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
            'form' => $form->createView(),
            'parcelMachines' => $parcelMachines,
            'user' => $user,
            'isUserOrder' => $isUserOrder,
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

    /**
     * @Route("/locations", name="app.order.locations")
     */
    public function locationsAction(Request $request, Session $session)
    {
        if ($request->isXmlHttpRequest()) {
            $itsXML = 'yes, its XML';
            $customerCoordinateX = $request->get('coordinateX');
            $customerCoordinateY = $request->get('coordinateY');
        } else {
            $itsXML = 'i will return you to NEW ORDER!!!!!';
            $customerCoordinateX = 54.9231038;
            $customerCoordinateY = 23.8208222;
        }
//        var_dump($request->request->get('data'));die;
//        dump($request->query->get('data'));
//        die;
//        $parcelMachines = $this->get(GeolocationService::class)->getParcelMachines('https://www.omniva.lt/locations.json');

        $customerCoordinateX = $request->get('coordinateX');
        $customerCoordinateY = $request->get('coordinateY');

//        $customerCoordinateX = 54.923072999999995;
//        $customerCoordinateY = 23.820887199999998;
//        var_dump($customerCoordinateX, $customerCoordinateY);die;

        $parcelMachines = $session->get('parcelMachines');
        $machinesArray = [];
//        $coordinatesArray = [];
//
//        foreach ($parcelMachines as $machine){
        ////            array_push($machinesArray, )
//            $coordinate = ['x' => $machine->getCoordinateX(), 'y' => $machine->getCoordinateY()];
//            array_push($coordinatesArray, $coordinate);
//        }
//        var_dump($coordinatesArray);die;

        $parcelMachines = $this->get(GeolocationService::class)->addDistanceToMachines(
            $parcelMachines,
            $customerCoordinateX,
            $customerCoordinateY
        );



        foreach ($parcelMachines as $machine) {
//            array_push($machinesArray, )
            array_push($machinesArray, $machine->getMachineArray());
        }
//        var_dump($machinesArray);die;
        usort($machinesArray, function ($a, $b) {
            return $a['distanceValue'] <=> $b['distanceValue'];
        });

//        var_dump($machinesArray);die;
//        var_dump($parcelMachines); die;
//        var_dump($parcelMachines);
//        return new JsonResponse($request->request->get('data'));
        return new JsonResponse(
            [
            'parcelMachines' =>  $machinesArray,
            'coordinates' => $request->get('coordinateX') . ' AND ' . $request->get('coordinateY') . $itsXML,
            ],
            200
        );
    }
}
