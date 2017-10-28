<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.10.27
 * Time: 15.20
 */

namespace AppBundle\Security;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
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
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManager $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
    }

    public function getCredentials(Request $request)
    {
        if($request->getPathInfo() != '/connect/facebook/check'){
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

        $userArray = $facebookUser->toArray();

        //if user exist, return him
        $existingUser = $this->em->getRepository('AppBundle:User')
            ->findOneBy(['facebookId' => $facebookUser->getId()]);
        if($existingUser){
            return $existingUser;
        }


        $user = new User();

        $user->setEmail($userArray['email']);
        $user->setName($userArray['first_name']);
        $user->setSurname($userArray['last_name']);
        $user->setFacebookId($facebookUser->getId());
        $this->em->persist($user);
        $this->em->flush();

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