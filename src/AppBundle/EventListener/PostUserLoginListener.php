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
use AppBundle\Service\FacebookInfoService;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class PostUserLoginListener
 * @package AppBundle\EventListener
 */
class PostUserLoginListener
{
    /**
     * @var FacebookInfoService
     */
    private $facebook;

    /**
     * PostUserLoginListener constructor.
     *
     * @param FacebookInfoService $facebook
     */
    public function __construct(FacebookInfoService $facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @param UserLoginEvent $event
     */
    public function onUserLoginSaveImage(UserLoginEvent $event)
    {
        $pictureUrl = $this->facebook->getPersonPictureUrl();

        $fileName = explode("/p200x200/", $pictureUrl);
        $fileName = explode("?", $fileName[1]);
        $fileName = $fileName[0];

        $filesystem = new Filesystem();
        $filesystem->copy(
            $pictureUrl,
            __DIR__ . '/../../../web/uploads/facebook_images/' . $fileName,
            false
        );

        $event->setPictureUrl('uploads/facebook_images/' . $fileName);
    }
}
