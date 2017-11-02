<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.10.30
 * Time: 20.46
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        date_default_timezone_set('Europe/Vilnius');
        $this->em = $em;
    }

    public function createUser($userArray)
    {
        $user = new User();
        $user->setEmail($userArray['email']);
        $user->setFirstName($userArray['first_name']);
        $user->setLastName($userArray['last_name']);
        $user->setFacebookId($userArray['id']);
        $user->setPictureUrl($userArray['picture_url']);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function updateUser($userArray)
    {
        $existingUser = $this->em->getRepository('AppBundle:User')
            ->findOneBy(['facebookId' => $userArray['id']]);
        if ($existingUser) {
            $datetime = $this->getCurrentTime();
            if ($existingUser->getPictureUrl() != $userArray['picture_url']) {
                $existingUser->setPictureUrl($userArray['picture_url']);
            }
            $loginCount = $existingUser->getLoginCount();
            $existingUser->setLoginCount($loginCount + 1);
            $existingUser->setLoggedDate($datetime);
            $this->em->persist($existingUser);
            $this->em->flush();
            return $existingUser;
        }
        return null;
    }

    private function getCurrentTime()
    {
        $now = date('Y-m-d H:i:s', time());
        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $now);
        return $datetime;
    }
}
