<?php

namespace App\Services;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;

class ArticleService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function handleArticle($name)
    {
        $faker = \Faker\Factory::create();

        $article = $faker->realText(100);

        return 'Super cool article written by '.$name.' '.$article;
    }

    public function save(User $user, Article $article)
    {
        $article->setUser($user);
        $this->doctrine->getManager()->persist($article);
        $this->doctrine->getManager()->flush();

        return $article;
    }
}
