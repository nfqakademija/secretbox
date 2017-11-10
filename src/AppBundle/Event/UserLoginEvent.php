<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.11.8
 * Time: 19.28
 */

namespace AppBundle\Event;

use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserLoginEvent extends Event
{
    const NAME = 'user.login';
    protected $user;

    /**
     * UserLoginEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->user->getPictureUrl();
    }

    /**
     * @param blob $picture
     */
    public function setPicture($picture)
    {
        $this->user->setPicture($picture);
    }
}
