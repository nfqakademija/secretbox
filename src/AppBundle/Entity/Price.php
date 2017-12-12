<?php

namespace AppBundle\Entity;

use Doctrine\DBAL\Types\DecimalType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table(name="prices")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PriceRepository")
 */
class Price
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DecimalType
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var DecimalType
     *
     * @ORM\Column(name="vat_rate", type="decimal", precision=4, scale=3)
     */
    private $vatRate;

    /**
     * @ORM\Column(name="box_size", type="enum_price_box_size")
     */
    private $boxSize;

    /**
     * @ORM\Column(name="valid_from", type="datetime")
     */
    private $validFrom;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param DecimalType $price
     *
     * @return Price
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return DecimalType
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set vat
     *
     * @param string $vatRate
     *
     * @return Price
     */
    public function setVatRate($vatRate)
    {
        $this->vatRate = $vatRate;

        return $this;
    }

    /**
     * Get vat
     *
     * @return string
     */
    public function getVatRate()
    {
        return $this->vatRate;
    }

    /**
     * @return string
     */
    public function getBoxSize()
    {
        return $this->boxSize;
    }

    /**
     * @param string $boxSize
     *
     * @return Price
     */
    public function setBoxSize($boxSize)
    {
        $this->boxSize = $boxSize;
        return $this;
    }



    /**
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @param \DateTime $validFrom
     *
     * @return Price
     */
    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;

        return $this;
    }
}
