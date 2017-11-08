<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.10.27
 * Time: 15.20
 */

namespace AppBundle\Service;

use AppBundle\Event\UserLoginEvent;
use AppBundle\EventListener\PostUserLoginListener;
use Doctrine\ORM\EntityManager;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookAuthenticatorService extends SocialAuthenticator
{
    private $em;
    private $clientRegistry;
    private $router;
    private $userService;
    private $dispatcher;
    private $listener;


    public function __construct(
        EntityManager $em,
        ClientRegistry $clientRegistry,
        RouterInterface $router,
                                UserService $userService,
        EventDispatcher $dispatcher,
                                PostUserLoginListener $listener
    ) {
        $this->em = $em;
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
        $this->userService = $userService;
        $this->dispatcher = $dispatcher;
        $this->listener = $listener;
        $this->dispatcher->addListener('user.login', array($this->listener, 'onUserLoginSaveImage'));
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/connect/facebook/check') {
            return null;
        }

        return $this->fetchAccessToken($this->getFacebookClient());
    }

    private function getFacebookClient()
    {
        return $this->clientRegistry
            ->getClient('facebook_main');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        $facebookUserArray = $facebookUser->toArray();

        $existingUser = $this->userService->updateUser($facebookUserArray);

        if ($existingUser) {
            $user = $existingUser;
        } else {
            $user = $this->userService->createUser($facebookUserArray);
        }

        $event = new UserLoginEvent($user);
        $this->dispatcher->dispatch(UserLoginEvent::NAME, $event);

        $this->em->getRepository('AppBundle:User')->saveUser($user);

        return $user;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // TODO: Implement start() method.
    }
}
