<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.10.27
 * Time: 15.20
 */

namespace AppBundle\Security;


use AppBundle\Service\UserService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $router;
    private $userService;

    public function __construct(ClientRegistry $clientRegistry, RouterInterface $router, UserService $userService)
    {
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
        $this->userService = $userService;
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/connect/facebook/check') {
            return;
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
            return $existingUser;
        }

        $user = $this->userService->createUser($facebookUserArray);
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