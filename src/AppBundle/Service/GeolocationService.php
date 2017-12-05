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

    /**
     * GeolocationService constructor.
     *
     * @param ContainerInterface $container
     * @param Session $session
     */
    public function __construct(ContainerInterface $container, Session $session)
    {
        $this->container = $container;
        $this->session = $session;
    }

    /**
     * @param string $locale
     *
     * @return array ParcelMachine
     */
    public function getParcelMachines($locale)
    {
        $availableCountries= ['LT'];

        $client = new Client();
        $response = $client->get(
            GeolocationService::OMNIVA_URL
        );

        $parcelMachines = [];

        $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        $commentLang = $locale == 'lt' ? 'comment_lit' : 'comment_eng';

        foreach ($data as $key => $item) {
            if (in_array($item['A0_NAME'], $availableCountries)) {
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
        }

        return $parcelMachines;
    }

    /**
     * @param array ParcelMachine $parcelMachines
     * @param float $customerCoordinateX
     * @param float $customerCoordinateY
     *
     * @return array ParcelMachine
     */
    public function addDistanceToMachines($parcelMachines, $customerCoordinateX, $customerCoordinateY)
    {
        //todo padaryti, kad origin imtu ir adresa
        $origin = [new CoordinateLocation(new Coordinate((float) $customerCoordinateX, (float) $customerCoordinateY))];
        $destinations = $this->getCoordinatesLocations($parcelMachines);

        $request = new DistanceMatrixRequest(
            $origin,
            $destinations
        );
        $request->setLanguage('lt');
        $response = $this->container->get('ivory.google_map.distance_matrix')->process($request);

        reset($parcelMachines);
        foreach ($response->getRows() as $row) {
            foreach ($row->getElements() as $element) {
                current($parcelMachines)
                    ->setDistanceTextToCustomer($element->getDistance()->getText())
                    ->setDistanceValueToCustomer($element->getDistance()->getValue());
                next($parcelMachines);
            }
        }

        return $parcelMachines;
    }

    /**
     * @param string $locale
     *
     * @return array
     */
    public function getOnlyNames($locale)
    {
        $parcelMachines = $this->getParcelMachines($locale);
        $onlyNames = [];
        foreach ($parcelMachines as $machine) {
            $onlyNames[$machine->getName()] = $machine->getName();
        }

        return $onlyNames;
    }

    /**
     * @param array ParcelMachine $parcelMachines
     *
     * @return array CoordinateLocation
     */
    private function getCoordinatesLocations($parcelMachines)
    {
        $locations = [];

        /** @var ParcelMachine $machine */
        foreach ($parcelMachines as $machine) {
            $coordinateLocation = new CoordinateLocation(new Coordinate($machine->getCoordinateY(), $machine->getCoordinateX()));
            array_push($locations, $coordinateLocation);
        }

        return $locations;
    }
}
