<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.11.8
 * Time: 18.37
 */

namespace AppBundle\EventListener;

use AppBundle\Event\UserLoginEvent;

class PostUserLoginListener
{
    /**
     * @param UserLoginEvent $event
     */
    public function onUserLoginSaveImage(UserLoginEvent $event)
    {
        $pictureUrl = $event->getPictureUrl();
        $picture =  file_get_contents($pictureUrl);
        $picture = 'data:image/jpeg;base64,'.base64_encode($picture);
        $event->setPicture($picture);
    }
}
