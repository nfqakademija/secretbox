<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.12.13
 * Time: 10.33
 */

namespace AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class EventsAndCustomersCountService
{
    private $em;

    /**
     * EventsAndCustomersCountService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getEventsAndCustomers()
    {
        $ongoing = $this->countCurrentSecrets();
        $revealed = $this->countRevealedSecrets();
        $customers = $this->countAllCustmoers();
        $numbers = array_merge($ongoing, $revealed, $customers);

        return $numbers;
    }

    /**
     * @return array
     */
    private function countCurrentSecrets()
    {
        return $this->em->getRepository(Order::class)->getCountCurrentSecrets();
    }

    /**
     * @return array
     */
    private function countRevealedSecrets()
    {
        return $this->em->getRepository(Order::class)->getCountRevealedSecrets();
    }

    /**
     * @return array
     */
    private function countAllCustmoers()
    {
        return $this->em->getRepository(User::class)->countAllUsers();
    }
}
