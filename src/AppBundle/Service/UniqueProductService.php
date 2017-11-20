<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.11.18
 * Time: 10.56
 */

namespace AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

class UniqueProductService
{
    private $em;

    /**
     * UniqueProductService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param integer $userId
     * @param integer $daysToRevealSecret
     *
     * @return null|integer
     */
    public function getUserUnusedProducts($userId, $daysToRevealSecret)
    {
        $validOrderDate = new \DateTime();
        $validOrderDate->modify('-' . $daysToRevealSecret . ' days');
        $revealedProducts = $this->em->getRepository(Order::class)->getUserRevealedProducts($userId, $validOrderDate);

        $validProductDate = new \DateTime();
        $validProductDate->modify('+' . $daysToRevealSecret . ' days');
        $newProducts = $this->em->getRepository(Product::class)->getUniqueProducts($revealedProducts, $validProductDate);

        if(empty($newProducts)){

            return null;
        } else {
            $uniqueProduct = $newProducts[array_rand($newProducts, 1)];

            return $uniqueProduct['id'];
        }
    }
}
