<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 */
class Order
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     */
    private $product;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ordered_at", type="datetime")
     */
    private $orderedAt;

    /**
     * @var float
     *
     * @ORM\Column(name="selling_price", type="float")
     */
    private $sellingPrice;

    /**
     * @ORM\Column(name="status", type="string")
     */
    private $status = "new";

    /**
     * @ORM\Column(name="delivery_address", type="string")
     */
    private $deliveryAddress;

    /**
     * @ORM\Column(name="parcel_machine_delivery_address", type="string")
     */
    private $parcelMachineDeliveryAddress;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->orderedAt = new \DateTime();
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
     * Set orderDate
     *
     * @param \DateTime $orderedAt
     *
     * @return Order
     */
    public function setOrderedAt($orderedAt)
    {
        $this->orderedAt = $orderedAt;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime
     */
    public function getOrderedAt()
    {
        return $this->orderedAt;
    }

    /**
     * @return float
     */
    public function getSellingPrice()
    {
        return $this->sellingPrice;
    }

    /**
     * @param float $sellingPrice
     * @return Order
     */
    public function setSellingPrice($sellingPrice)
    {
        $this->sellingPrice = $sellingPrice;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }


    /**
     * @param string $deliveryAddress
     * @return Order
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }


    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Order
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     *
     * @return Order
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParcelMachineDeliveryAddress()
    {
        return $this->parcelMachineDeliveryAddress;
    }

    /**
     * @param mixed $parcelMachineDeliveryAddress
     *
     * @return Order
     */
    public function setParcelMachineDeliveryAddress($parcelMachineDeliveryAddress)
    {
        $this->parcelMachineDeliveryAddress = $parcelMachineDeliveryAddress;
        return $this;
    }



    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getOrderedAt()->format('Y-m-d h:m') . ' ' . $this->getProduct();
    }


    //todo remove this nesamone
    public function getOrderCountdown()
    {
        $orderCountdown = $this->orderedAt;
        $orderCountdown->modify('+14 day');
        return $orderCountdown;
    }
}
