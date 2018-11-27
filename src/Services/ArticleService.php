<?php

namespace App\Services;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ArticleService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ManagerRegistry $doctrine, ContainerInterface $container)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
    }

//    public function handleArticle($name)
//    {
//        $faker = \Faker\Factory::create();
//
//        $article = $faker->realText(100);
//
//        return 'Super cool article written by '.$name.' '.$article;
//    }

    public function save(User $user, Article $article)
    {
        $article->setUser($user);
        $this->doctrine->getManager()->persist($article);
        $this->doctrine->getManager()->flush();

        return $article;
    }

    public function list($request)
    {
        $query = $this->doctrine->getRepository(Article::class)->findAll();
        $contaiter = $this->container;
        $pagenator = $contaiter->get('knp_paginator');

        $result = $pagenator->paginate(
          $query,
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 5)
        );

        return $result;
    }
}
