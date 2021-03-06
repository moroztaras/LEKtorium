<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getLatestComments($limit = 10, $approved = true)
    {
        $query = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id', 'DESC');
        if (false === is_null($approved)) {
            $query->andWhere('c.approved = :approved')
              ->setParameter('approved', $approved);
        }
        if (false === is_null($limit)) {
            $query->setMaxResults($limit);
        }

        return $query->getQuery()->getResult();
    }

    public function getCommentsForArticle($id, $approved = true)
    {
        $query = $this->createQueryBuilder('c')
          ->select('c')
          ->where('c.article = :id')
          ->addOrderBy('c.id', 'DESC')
          ->setParameter('id', $id);
        if (false === is_null($approved)) {
            $query->andWhere('c.approved = :approved')
              ->setParameter('approved', $approved);
        }

        return $query->getQuery()->getResult();
    }
}
