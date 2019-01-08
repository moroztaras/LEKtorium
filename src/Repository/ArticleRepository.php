<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findByWord($word)
    {
        $query = $this->createQueryBuilder('a')
          ->where('a.title LIKE :word')
          ->orWhere('a.text LIKE :word')
          ->setParameter('word', '%'.$word.'%');

        return $query->getQuery()->getResult();
    }

    public function getListArticles()
    {
        $query = $this->createQueryBuilder('a')
          ->where('a.approved = :status')
          ->orderBy('a.createdAt', 'DESC')
          ->setParameter('status', true);

        return $query->getQuery()->getResult();
    }

    public function getAdminListArticles()
    {
        $query = $this->createQueryBuilder('a')
          ->orderBy('a.createdAt', 'DESC');

        return $query->getQuery()->getResult();
    }
}
