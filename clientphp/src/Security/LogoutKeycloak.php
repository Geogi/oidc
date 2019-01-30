<?php
/**
 * Created by PhpStorm.
 * User: paul
 * Date: 1/30/19
 * Time: 6:30 PM
 */

namespace App\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutKeycloak implements LogoutSuccessHandlerInterface
{
    /** @var UrlGeneratorInterface */
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request)
    {
        $redirect = $this->router->generate(
            'logout_check',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $logoutUrl = getenv('KEYCLOAK_URL')
            . '/realms/'
            . getenv('KEYCLOAK_REALM')
            . '/protocol/openid-connect/logout?redirect_uri='
            . urlencode($redirect);
        return new RedirectResponse($logoutUrl);
    }
}
