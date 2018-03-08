<?php
// src/OC/PlatformBundle/Event/MessagePostEvent.php

namespace OC\PlatformBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class MessagePostEvent extends Event
{
    protected $message;
    protected $user;

    public function __construct(UserInterface $user, $message)
    {
        $this->message = $message;
        $this->user = $user;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        return $this->message = $message;
    }

    public function getUser()
    {
        return $this->user;
    }
}