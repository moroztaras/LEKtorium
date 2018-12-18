<?php

namespace App\Services;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
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

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $doctrine, ContainerInterface $container, PaginatorInterface $paginator)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->paginator = $paginator;
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
        return  $this->paginator->paginate(
          $this->doctrine->getRepository(Article::class)->findAll(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );
    }

    public function remove(Article $article)
    {
        $this->doctrine->getManager()->remove($article);
        $this->doctrine->getManager()->flush();

        return $article;
    }
}
