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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ParcelMachine
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     *
     * @return ParcelMachine
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return ParcelMachine
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return ParcelMachine
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     *
     * @return ParcelMachine
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return float
     */
    public function getCoordinateX()
    {
        return (float) $this->coordinateX;
    }

    /**
     * @param float $coordinateX
     *
     * @return ParcelMachine
     */
    public function setCoordinateX($coordinateX)
    {
        $this->coordinateX = $coordinateX;
        return $this;
    }

    /**
     * @return float
     */
    public function getCoordinateY()
    {
        return (float) $this->coordinateY;
    }

    /**
     * @param float $coordinateY
     *
     * @return ParcelMachine
     */
    public function setCoordinateY($coordinateY)
    {
        $this->coordinateY = $coordinateY;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return ParcelMachine
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getDistanceTextToCustomer()
    {
        return $this->distanceTextToCustomer;
    }

    /**
     * @param string $distanceTextToCustomer
     *
     * @return ParcelMachine
     */
    public function setDistanceTextToCustomer($distanceTextToCustomer)
    {
        $this->distanceTextToCustomer = $distanceTextToCustomer;
        return $this;
    }

    /**
     * @return integer
     */
    public function getDistanceValueToCustomer()
    {
        return $this->distanceValueToCustomer;
    }

    /**
     * @param integer $distanceValueToCustomer
     *
     * @return ParcelMachine
     */
    public function setDistanceValueToCustomer($distanceValueToCustomer)
    {
        $this->distanceValueToCustomer = $distanceValueToCustomer;
        return $this;
    }

    /**
     * @return array
     */
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
}
