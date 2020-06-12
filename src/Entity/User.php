<?php


namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    protected $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

    public function __toString()
    {
        return $this->username;
    }

}