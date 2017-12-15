<?php

namespace AppBundle\Entity;

use Doctrine\DBAL\Types\DecimalType;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * Order
 *
 * @ORM\Table(name="orders", indexes={@Index(name="search_idx", columns={"id", "user_id", "product_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 */
class Order
{
    const ORDER_REVEAL_TIME = 1209600; //seconds, 14 days
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
     * @var \DateTime
     *
     * @ORM\Column(name="order_reveal_until", type="datetime")
     */
    private $orderRevealUntil;

    /**
     * @var DecimalType
     *
     * @ORM\Column(name="selling_price", type="decimal", precision=10, scale=2)
     */
    private $sellingPrice;

    /**
     * @ORM\Column(name="status", type="enum_order_status")
     */
    private $status = "new";

    /**
     * @ORM\Column(name="delivery_address", type="string")
     */
    private $deliveryAddress;

    /**
     * @ORM\Column(name="delivery_type", type="enum_delivery_type")
     */
    private $deliveryType;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->orderedAt = new \DateTime();
        $this->orderRevealUntil = (new \DateTime())->modify('+' . self::ORDER_REVEAL_TIME . ' seconds');
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
     * @return string
     */
    public function getSellingPrice()
    {
        return $this->sellingPrice;
    }

    /**
     * @param string $sellingPrice
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
     * @return \DateTime
     */
    public function getOrderRevealUntil(): \DateTime
    {
        return $this->orderRevealUntil;
    }

    /**
     * @param \DateTime $orderRevealUntil
     *
     * @return Order
     */
    public function setOrderRevealUntil(\DateTime $orderRevealUntil): Order
    {
        $this->orderRevealUntil = $orderRevealUntil;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryType()
    {
        return $this->deliveryType;
    }

    /**
     * @param string $deliveryType
     *
     * @return Order
     */
    public function setDeliveryType($deliveryType)
    {
        $this->deliveryType = $deliveryType;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getOrderedAt()->format('Y-m-d h:m') . ' ' . $this->getProduct();
    }
}
