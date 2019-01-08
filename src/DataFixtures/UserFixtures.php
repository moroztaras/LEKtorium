<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
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
        $admin
          ->setFirstName('Taras')
          ->setLastName('Moroz')
          ->setRoles(['ROLE_SUPER_ADMIN'])
          ->setEmail('moroztaras@i.ua')
          ->setPassword($this->passwordEncoder->encodePassword($admin, 'moroztaras'));

        $manager->persist($admin);

        $user_reader = new User();
        $user_reader
          ->setFirstName('ReaderFirstName')
          ->setLastName('ReaderLastName')
          ->setRoles(['ROLE_READER'])
          ->setEmail('reader@mail.ua')
          ->setPassword($this->passwordEncoder->encodePassword($user_reader, 'reader'));

        $manager->persist($user_reader);

        $user_blogger = new User();
        $user_blogger
          ->setFirstName('BloggerFirstName')
          ->setLastName('BloggerLastName')
          ->setRoles(['ROLE_BLOGGER'])
          ->setEmail('blogger@mail.ua')
          ->setPassword($this->passwordEncoder->encodePassword($user_blogger, 'blogger'));

        $manager->persist($user_blogger);

        for ($i = 1; $i <= 25; ++$i) {
            $article = new Article();
            $article
              ->setTitle('Title article '.$i)
              ->setText('Fixtures are used to load a "fake" set of data into a database that can then be used for testing or to help give you some interesting data while you\'re developing your application. This bundle makes creating fixtures easy, and supports the ORM (MySQL, PostgreSQL, SQLite, etc.).')
              ->setUser($admin)
              ->setImageName('article_image.jpg');

            for ($j = 1; $j <= 5; ++$j) {
                $comment = new Comment();
                $comment
                  ->setUser($admin)
                  ->setComment('Comment '.$j.' for "Title article '.$i.'"')
                  ->setArticle($article);

                $manager->persist($comment);

                $tag = new Tag();
                $tag
                  ->setName('Tag'.$j)
                  ->setArticle($article);

                $manager->persist($tag);
            }
            $manager->persist($article);
        }

        $manager->flush();
    }
}
