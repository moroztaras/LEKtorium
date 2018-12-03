<?php

namespace App\Services;

use App\Entity\User;
use App\Event\PasswordEnteringEvent;
use App\EventListener\PasswordListener;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var PasswordListener
     */
    private $listener;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserService constructor.
     */
    public function __construct(EventDispatcherInterface $dispatcher, ManagerRegistry $doctrine, PasswordListener $listener, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->dispatcher = $dispatcher;
        $this->doctrine = $doctrine;
        $this->listener = $listener;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function save(User $user)
    {
//        $event = new PasswordEnteringEvent($user);
//        $this->dispatcher
//          ->addListener(
//            PasswordEnteringEvent::NAME,
//            [$this->listener, 'onPasswordEnteringEvent']);
//        $encodePassword = $this->dispatcher->dispatch(PasswordEnteringEvent::NAME, $event)->getUser()->getPassword();
        $encodePassword = $this->passwordEncoder->encodePassword($user,$user->getPlainPassword());
        $user->setPassword($encodePassword);
        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();

        return $this;
    }
}
