<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class PasswordEnteringEvent extends Event
{
    const NAME = 'password.entering';
    protected $user;

    public function __construct($password)
    {
        $this->user = $password;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
}
