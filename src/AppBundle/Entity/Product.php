<?php

namespace AppBundle\Entity;

use Doctrine\DBAL\Types\DecimalType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\Column(name="gender", type="enum_gender")
     */
    private $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="age_min", type="integer", length=3)
     */
    private $ageMin = 10;

    /**
     * @var integer
     *
     * @ORM\Column(name="age_max", type="integer", length=3)
     */
    private $ageMax = 99;

    /**
     * @var string
     *
     * @ORM\Column(name="supplier", type="string")
     */
    private $supplier;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_name", type="string", length=255)
     */
    private $facebookName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="valid_from", type="datetime")
     */
    private $validFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="valid_to", type="datetime")
     */
    private $validTo;

    /**
     * @var DecimalType
     *
     * @ORM\Column(name="supplier_price", type="decimal", precision=10, scale=2)
     */
    private $supplierPrice;

    /**
     * @var DecimalType
     *
     * @ORM\Column(name="market_value", type="decimal", precision=10, scale=2)
     */
    private $marketValue;

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
     * Set title
     *
     * @param string $title
     *
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $supplierPrice
     *
     * @return Product
     */
    public function setSupplierPrice($supplierPrice)
    {
        $this->supplierPrice = $supplierPrice;

        return $this;
    }

    /**
     * Get price
     *
     * @return DecimalType
     */
    public function getSupplierPrice()
    {
        return $this->supplierPrice;
    }

    /**
     * @return DecimalType
     */
    public function getMarketValue()
    {
        return $this->marketValue;
    }

    /**
     * @param DecimalType $marketValue
     *
     * @return Product
     */
    public function setMarketValue($marketValue)
    {
        $this->marketValue = $marketValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param string $supplier
     *
     * @return Product
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
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
     * @return Product
     */
    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getValidTo()
    {
        return $this->validTo;
    }

    /**
     * @param \DateTime $validTo
     *
     * @return Product
     */
    public function setValidTo($validTo)
    {
        $this->validTo = $validTo;
        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookName()
    {
        return $this->facebookName;
    }

    /**
     * @param string $facebookName
     *
     * @return Product
     */
    public function setFacebookName($facebookName)
    {
        $this->facebookName = $facebookName;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return Product
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return int
     */
    public function getAgeMin(): int
    {
        return $this->ageMin;
    }

    /**
     * @param int $ageMin
     *
     * @return Product
     */
    public function setAgeMin(int $ageMin): Product
    {
        $this->ageMin = $ageMin;
        return $this;
    }

    /**
     * @return int
     */
    public function getAgeMax(): int
    {
        return $this->ageMax;
    }

    /**
     * @param int $ageMax
     *
     * @return Product
     */
    public function setAgeMax(int $ageMax): Product
    {
        $this->ageMax = $ageMax;
        return $this;
    }



    public function __toString()
    {
        return (string) $this->getTitle();
    }
}
