<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.11.29
 * Time: 11.09
 */

namespace AppBundle\Service;

use GuzzleHttp\Client;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Service\Base\Location\CoordinateLocation;
use Ivory\GoogleMap\Service\DistanceMatrix\Request\DistanceMatrixRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GeolocationService
{
    const OMNIVA_URL = 'https://www.omniva.lt/locations.json';

    private $container;
    private $session;

    public function __construct(ContainerInterface $container, Session $session)
    {
        $this->container = $container;
        $this->session = $session;
    }

    public function getParcelMachines($locale)
    {
        $availableCountries= ['LT'];

        $client = new Client();
        $response = $client->get(
            GeolocationService::OMNIVA_URL
        );

        $parcelMachines = [];

        $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
//        echo $response->getStatusCode();
//        var_dump($data);die;
        $commentLang = $locale == 'lt' ? 'comment_lit' : 'comment_eng';

        foreach ($data as $key => $item) {
            if (in_array($item['A0_NAME'], $availableCountries)) {
//                unset($data[$key]);
                $machine = (new ParcelMachine())
                    ->setName($item['NAME'])
                    ->setCountryCode($item['A0_NAME'])
                    ->setCity($item['A1_NAME'])
                    ->setAddress($item['A2_NAME'])
                    ->setZipCode($item['ZIP'])
                    ->setCoordinateX($item['X_COORDINATE'])
                    ->setCoordinateY($item['Y_COORDINATE'])
                    ->setComment($item[$commentLang]);
                array_push($parcelMachines, $machine);
            }

//            echo $item['A0_NAME'] . PHP_EOL;
        }
//        var_dump($parcelMachines);die;
        return $parcelMachines;

//        var_dump(\GuzzleHttp\json_decode($body->getContents()));die;
//        isJson()
//        $request->getBody();
//        var_dump($request->getBody(); die;
    }

    public function addDistanceToMachines($parcelMachines, $customerCoordinateX, $customerCoordinateY)
    {
        $origin = [new CoordinateLocation(new Coordinate((float) $customerCoordinateX, (float) $customerCoordinateY))];
        $destinations = $this->getCoordinatesLocations($parcelMachines);
//        var_dump($origin);die;

        $request = new DistanceMatrixRequest(
//            [new Coordinate('58.4926417741916', '26.6822723174854')] ,
//            [new Coordinate('54.923076099999996', '23.8207749')]
//            [new AddressLocation('Mickeviciaus g 11, Kaunas')],
            [new CoordinateLocation(new Coordinate((float) $customerCoordinateX, (float) $customerCoordinateY))],
            $destinations
//            [new AddressLocation('Birzisku 3, Kaunas')]
        );
        $request->setLanguage('lt');
//        $request->addOrigins(
//            [new Coordinate('58.4926417741916', '26.6822723174854')]
//        );
        $response = $this->container->get('ivory.google_map.distance_matrix')->process($request);


//        var_dump($response->getStatus());die;

        reset($parcelMachines);
        foreach ($response->getRows() as $row) {
            foreach ($row->getElements() as $element) {
                current($parcelMachines)
                    ->setDistanceTextToCustomer($element->getDistance()->getText())
                    ->setDistanceValueToCustomer($element->getDistance()->getValue());
//                var_dump(current($parcelMachines));
                next($parcelMachines);
//                var_dump($element);
            }
        }
//        var_dump($parcelMachines);die;

        return $parcelMachines;
    }

    public function getOnlyNames($locale)
    {
        $parcelMachines = $this->getParcelMachines($locale);
        $onlyNames = [];
        foreach ($parcelMachines as $machine) {
//            array_push($onlyNames, $machine->getName());
            $onlyNames[$machine->getName()] = $machine->getName() . 'lalalalalaal';
        }

        return $onlyNames;
    }

    private function getCoordinatesLocations($parcelMachines)
    {
//        $parcelMachines = $this->session->get('parcelMachines');
        $array = [];
        foreach ($parcelMachines as $machine) {
            $coordinateLocation = new CoordinateLocation(new Coordinate($machine->getCoordinateY(), $machine->getCoordinateX()));
            array_push($array, $coordinateLocation);
        }


//        foreach ($coordinatesArray as $coordinate){
//            $new = new CoordinateLocation(new Coordinate($coordinate['y'], $coordinate['x']));
//            array_push($array, $new);
//        }
        return $array;
    }
}
