<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.10.27
 * Time: 15.20
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Event\UserLoginEvent;
use Doctrine\ORM\EntityManager;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
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
    private $session;

    /**
     * FacebookAuthenticatorService constructor.
     *
     * @param EntityManager            $em
     * @param ClientRegistry           $clientRegistry
     * @param RouterInterface          $router
     * @param UserService              $userService
     * @param EventDispatcherInterface $dispatcher
     * @param Session                  $session
     */
    public function __construct(
        EntityManager $em,
        ClientRegistry $clientRegistry,
        RouterInterface $router,
        UserService $userService,
        EventDispatcherInterface $dispatcher,
        Session $session
    ) {
        $this->em = $em;
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
        $this->userService = $userService;
        $this->dispatcher = $dispatcher;
        $this->session = $session;
    }

    /**
     * @param Request $request
     *
     * @return AccessToken|null
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/connect/facebook/check') {
            return null;
        }

        return $this->fetchAccessToken($this->getFacebookClient());
    }

    /**
     * @return OAuth2Client
     */
    private function getFacebookClient()
    {
        return $this->clientRegistry
            ->getClient('facebook_main');
    }

    /**
     * @param AccessToken           $credentials  *
     * @param UserProviderInterface $userProvider
     *
     * @return User|null|object
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);


        $this->session->set('facebook_user_access_token', $credentials->getToken());

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
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
    }
}
