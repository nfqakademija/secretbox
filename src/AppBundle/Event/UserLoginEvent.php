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

/**
 * Class UserLoginEvent
 *
 * @package AppBundle\Event
 */
class UserLoginEvent extends Event
{
    const NAME = 'user.login';

    /**
     * @var User
     */
    protected $user;

    /**
     * UserLoginEvent constructor.
     *
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
     * @param string $picture
     */
    public function setPictureUrl($picture)
    {
        $this->user->setPictureUrl($picture);
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->user->getFacebookId();
    }
}
