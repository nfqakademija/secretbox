<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PriceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PriceRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getCurrentPrices()
    {
        $result = $this->_em->getConnection()->executeQuery('
            SELECT p1.box_size, p1.price FROM prices AS p1
            WHERE valid_from IN 
            (
              SELECT MAX(p2.valid_from) FROM prices AS p2 
              WHERE p2.box_size = p1.box_size AND p2.valid_from < NOW()
            )
        ')->fetchAll();

        return $result;
    }
}
