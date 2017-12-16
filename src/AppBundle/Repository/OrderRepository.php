<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends EntityRepository
{
    /**
     * @param int $userId
     *
     * @param \DateTime $validDate
     *
     * @param string $boxSize
     *
     * @return Product[]
     */
    public function getUserRevealedProducts($userId, $validDate, $boxSize)
    {
        $rsm = new ResultSetMapping();
        $query = $this->_em->createQuery(
            "SELECT p.id FROM AppBundle:Order AS o
                  LEFT JOIN AppBundle:Product AS p
                  WITH o.product = p.id
                  WHERE (o.user=:userId AND p.boxSize=:boxSize) AND
	                (o.status='revealed' OR 
                    (o.status='new' AND o.orderedAt > :validDate))",
            $rsm
        );
        $query
            ->setParameter('userId', $userId)
            ->setParameter('validDate', $validDate)
            ->setParameter('boxSize', $boxSize);
        $products = $query->getResult();

        return $products;
    }

    /**
     * @param Order $order
     */
    public function saveOrder(Order $order)
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }

    /**
     * @param int $userId
     *
     * @return Order[]
     */
    public function getUserRevealedOrders($userId)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('orders.orderedAt', 'products.title', 'products.description', 'products.supplier')
            ->from('AppBundle:Order', 'orders')
            ->innerJoin(
                'AppBundle:Product',
                'products',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'orders.product = products.id'
            )
            ->where('orders.user = :user')
            ->andWhere('orders.status = :status')
            ->setParameter('user', $userId)
            ->setParameter('status', 'revealed');
        $orders = $queryBuilder->getQuery()->getResult();

        return $orders;
    }

    /**
     * @param int $userId
     *
     * @return Order[]
     */
    public function getUserSecrets($userId)
    {
        $result = $this->_em->createQueryBuilder()
            ->select('orders')
            ->from('AppBundle:Order', 'orders')
            ->where('orders.user = :user')
            ->andWhere('orders.status = :status')
            ->setParameter('user', $userId)
            ->setParameter('status', 'new')
            ->orderBy('orders.orderRevealUntil', 'DESC')
            ->getQuery()
            ->getResult();
//            [
//            'user' =>  $userId,
//            'status' => 'new'
//            ],
//            ['Order' => 'orderedAt DESC']


        return $result;
    }

    /**
     * @return array
     */
    public function getCountCurrentSecrets()
    {
        $result = $this->_em->getConnection()->executeQuery('
            SELECT COUNT(id) as ongoing FROM orders
            WHERE status IN ("new", "delivered")
        ')->fetchAll();

        return $result[0];
    }

    /**
     * @return array
     */
    public function getCountRevealedSecrets()
    {
        $result = $this->_em->getConnection()->executeQuery('
            SELECT COUNT(id) as revealed FROM orders
            WHERE status IN ("revealed")
        ')->fetchAll();

        return $result[0];
    }
}
