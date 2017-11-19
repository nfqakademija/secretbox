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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

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
     * @return mixed
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param mixed $supplier
     *
     * @return Product
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @param mixed $validFrom
     * @return Product
     */
    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidTo()
    {
        return $this->validTo;
    }

    /**
     * @param mixed $validTo
     * @return Product
     */
    public function setValidTo($validTo)
    {
        $this->validTo = $validTo;
        return $this;
    }
}
