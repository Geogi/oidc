<?php
/**
 * Created by PhpStorm.
 * User: paul
 * Date: 1/29/19
 * Time: 6:19 PM
 */

namespace App\Security;

use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OidcAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $router;

    public function __construct(
        ClientRegistry $clientRegistry,
        UrlGeneratorInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'login_check';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getOidcClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials);
    }

    private function getOidcClient()
    {
        return $this->clientRegistry->getClient('oidc');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            $this->router->generate('login'),
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}
