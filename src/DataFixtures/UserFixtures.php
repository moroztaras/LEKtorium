<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($admin, 'moroztaras');
        $admin
          ->setFirstName('Taras')
          ->setLastName('Moroz')
          ->setRoles(['ROLE_ADMIN'])
          ->setEmail('moroztaras@i.ua')
          ->setPassword($encodedPassword);
        $manager->persist($admin);

        $user = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'user');
        $user
          ->setFirstName('UserFirstName')
          ->setLastName('UserLastName')
          ->setRoles(['ROLE_USER'])
          ->setEmail('user@mail.ua')
          ->setPassword($encodedPassword);
        $manager->persist($user);

        $manager->flush();
    }
}
