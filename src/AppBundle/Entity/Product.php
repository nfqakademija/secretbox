<?php

namespace AppBundle\Entity;

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
     * @ORM\Column(name="facebook_name", type="string", length=255)
     */
    private $facebookName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string")
     */
    private $gender;

    /**
     * @var array
     *
     * @ORM\Column(name="age_range", type="json_array")
     */
    private $ageRange;

    /**
     * @var string
     *
     * @ORM\Column(name="supplier", type="string")
     */
    private $supplier;

    /**
     * @ORM\Column(name="valid_from", type="datetime")
     */
    private $validFrom;

    /**
     * @ORM\Column(name="valid_to", type="datetime")
     */
    private $validTo;

    /**
     * @var float
     *
     * @ORM\Column(name="supplier_price", type="float")
     */
    private $supplierPrice;

    public function __construct()
    {
        $this->ageRange = [
            'min' => 10,
            'max' => 99
        ];
    }

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
     * @return float
     */
    public function getSupplierPrice()
    {
        return $this->supplierPrice;
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
     * @return array
     */
    public function getAgeRange()
    {
        return $this->ageRange;
    }

    /**
     * @param array $ageRange
     *
     * @return Product
     */
    public function setAgeRange($ageRange)
    {
        $this->ageRange = $ageRange;
        return $this;
    }
}
