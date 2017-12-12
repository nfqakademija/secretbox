<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.12.7
 * Time: 11.05
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class OrderPriceService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $boxSize
     *
     * @return float
     */
    public function getCurrentPrices($boxSize)
    {
        $prices = $this->em->getRepository('AppBundle:Price')->getCurrentPrices();
        $orderPrice = 0;

        foreach ($prices as $price){
            if($price['box_size'] === $boxSize){
                $orderPrice = $price['price'];
            }
        }

        return (float) $orderPrice;
    }
}
