<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class ArticleLikeRepository extends EntityRepository
{
    public function findLikeArticle(User $user, Article $article)
    {
        $query = $this->createQueryBuilder('al');
        $query->where('al.user = :user');
        $query->setParameter('user', $user);
        $query->andWhere('al.article = :article');
        $query->setParameter('article', $article);

        return $query->getQuery()->getResult();
    }

    public function removeLikeArticle(User $user, Article $article)
    {
        $query = $this->createQueryBuilder('al');
        $query->delete();
        $query->where('al.user = :user');
        $query->setParameter('user', $user);
        $query->andWhere('al.article = :article');
        $query->setParameter('article', $article);

        return $query->getQuery()->getResult();
    }
}
