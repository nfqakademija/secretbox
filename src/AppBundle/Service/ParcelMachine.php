<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.11.29
 * Time: 12.28
 */

namespace AppBundle\Service;

class ParcelMachine
{
    private $name;
    private $countryCode;
    private $city;
    private $address;
    private $zipCode;
    private $coordinateX;
    private $coordinateY;
    private $comment;
    private $distanceTextToCustomer;
    private $distanceValueToCustomer;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return ParcelMachine
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $countryCode
     *
     * @return ParcelMachine
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     *
     * @return ParcelMachine
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     *
     * @return ParcelMachine
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param mixed $zipCode
     *
     * @return ParcelMachine
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCoordinateX()
    {
        return (float) $this->coordinateX;
    }

    /**
     * @param mixed $coordinateX
     *
     * @return ParcelMachine
     */
    public function setCoordinateX($coordinateX)
    {
        $this->coordinateX = $coordinateX;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCoordinateY()
    {
        return (float) $this->coordinateY;
    }

    /**
     * @param mixed $coordinateY
     *
     * @return ParcelMachine
     */
    public function setCoordinateY($coordinateY)
    {
        $this->coordinateY = $coordinateY;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     *
     * @return ParcelMachine
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistanceTextToCustomer()
    {
        return $this->distanceTextToCustomer;
    }

    /**
     * @param mixed $distanceTextToCustomer
     *
     * @return ParcelMachine
     */
    public function setDistanceTextToCustomer($distanceTextToCustomer)
    {
        $this->distanceTextToCustomer = $distanceTextToCustomer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistanceValueToCustomer()
    {
        return $this->distanceValueToCustomer;
    }

    /**
     * @param mixed $distanceValueToCustomer
     *
     * @return ParcelMachine
     */
    public function setDistanceValueToCustomer($distanceValueToCustomer)
    {
        $this->distanceValueToCustomer = $distanceValueToCustomer;
        return $this;
    }




    public function getMachineArray()
    {
        return [
            'name' => $this->name,
            'city' => $this->city,
            'address' => $this->address,
            'x' => $this->coordinateX,
            'y' => $this->coordinateY,
            'distanceText' => $this->distanceTextToCustomer,
            'distanceValue' => $this->distanceValueToCustomer,
            'comment' => $this->comment
        ];
    }

//    private function getDistanceToCustomer($customerCoordinateX, $customerCoordinateY){
//        return '100' . 'm';
//    }
}
