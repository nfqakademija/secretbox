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

    /**
     * UserService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        date_default_timezone_set('Europe/Vilnius');
        $this->em = $em;
    }

    /**
     * @param array $userArray
     *
     * @return User
     */
    public function createUser($userArray)
    {
        $user = new User();
        $user->setEmail($userArray['email']);
        $user->setFirstName($userArray['first_name']);
        $user->setLastName($userArray['last_name']);
        $user->setFacebookId($userArray['id']);
        $user->setPictureUrl($userArray['picture_url']);

        return $user;
    }

    /**
     * @param array $userArray
     *
     * @return User|null|object
     */
    public function updateUser($userArray)
    {
        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $userArray['email']]);
        if ($existingUser) {
            if ($existingUser->getPictureUrl() != $userArray['picture_url']) {
                $existingUser->setPictureUrl($userArray['picture_url']);
            }
            $loginCount = $existingUser->getLoginCount();
            $existingUser->setLoginCount($loginCount + 1);
            $existingUser->setLoggedDate(new \DateTime());

            return $existingUser;
        }

        return null;
    }
}
