<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.11.3
 * Time: 14.38
 */

namespace AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;

class OrderService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createNewOrder(Order $order)
    {
        $this->em->persist($order);
        $this->em->flush();
    }

    public function getUserSecrets($userId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('orders.orderDate', 'orders.deliveryAddress')
            ->from('AppBundle:Order', 'orders')
            ->where('orders.userId = :user')
            ->andWhere('orders.status = :status')
            ->setParameter('user', $userId)
            ->setParameter('status', 'new');

        $userSecrets = $queryBuilder->getQuery()->getResult();
        return $userSecrets;
    }

    public function getUserOrders($userId)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('orders.orderDate', 'products.title', 'products.description', 'products.supplier')
            ->from('AppBundle:Order', 'orders')
            ->innerJoin(
                'AppBundle:Product',
                'products',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'orders.productId = products.id'
            )
            ->where('orders.userId = :user')
            ->setParameter('user', $userId);
        $orders = $queryBuilder->getQuery()->getResult();

        return $orders;
    }
}
