<?php

namespace App\Security;

use League\OAuth2\Client\Token\AccessToken;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $keycloak;

    public function __construct(
        Keycloak $keycloak
    )
    {
        $this->keycloak = $keycloak;
    }

    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @param $username
     * @return UserInterface
     *
     */
    public function loadUserByUsername($username)
    {
        $user = new User();
        if (!$username instanceof AccessToken) {
            throw new UsernameNotFoundException(
                sprintf('Invalid username-like token class "%s"', get_class($username))
            );
        }
        $user->setToken($username);
        $resourceOwner = $this->keycloak->getResourceOwner($username);
        $user->setRealUsername($resourceOwner->toArray()['preferred_username']);
        return $user;
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @param UserInterface $user
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $user;
    }

    /**
     * Tells Symfony to use this provider for this User class.
     * @param $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
