<?php

namespace App\Services;

use App\Entity\User;
use App\Event\PasswordEnteringEvent;
use App\EventListener\PasswordListener;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * UserService constructor.
     */
    public function __construct(EventDispatcherInterface $dispatcher, ManagerRegistry $doctrine, PasswordListener $listener, UserPasswordEncoderInterface $passwordEncoder, PaginatorInterface $paginator, TokenGeneratorInterface $tokenGenerator)
    {
        $this->dispatcher = $dispatcher;
        $this->doctrine = $doctrine;
        $this->listener = $listener;
        $this->passwordEncoder = $passwordEncoder;
        $this->paginator = $paginator;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function save(User $user)
    {
//        $event = new PasswordEnteringEvent($user);
//        $this->dispatcher
//          ->addListener(
//            PasswordEnteringEvent::NAME,
//            [$this->listener, 'onPasswordEnteringEvent']);
//        $encodePassword = $this->dispatcher->dispatch(PasswordEnteringEvent::NAME, $event)->getUser()->getPassword();
        $encodePassword = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($encodePassword);
        $user->setApiToken($this->tokenGenerator->generateToken());
        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();

        return $this;
    }

    public function edit(User $user)
    {
        switch ($user->getTempRoles()) {
            case 'ROLE_READER':
                $user->setRoles(['ROLE_READER']);
                break;
            case 'ROLE_BLOGGER':
                $user->setRoles(['ROLE_BLOGGER']);
                break;
        }
        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();

        return $user;
    }

    public function list($request)
    {
        return  $this->paginator->paginate(
          $this->doctrine->getRepository(User::class)->findBy([], ['id' => 'DESC']),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );
    }

    public function countUsers()
    {
        return count($this->doctrine->getRepository(User::class)->findAll());
    }

    public function remove(User $user)
    {
        $this->doctrine->getManager()->remove($user);
        $this->doctrine->getManager()->flush();

        return $user;
    }
}
