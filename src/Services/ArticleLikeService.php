<?php

namespace App\Services;

use App\Entity\Article;
use App\Entity\ArticleLike;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;

class ArticleLikeService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * ArticleLikeService constructor.
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function status(User $user, Article $article, ArticleLike $articleLike)
    {
        $like = $this->doctrine->getRepository(ArticleLike::class)->findOneBy(['user' => $user, 'article' => $article]);

        if ($like) {
            return $this->remove($like);
        } else {
            return $this->add($user, $article, $articleLike);
        }
    }

    public function add(User $user, Article $article, ArticleLike $articleLike)
    {
        $articleLike->setUser($user);
        $articleLike->setArticle($article);
        $this->doctrine->getManager()->persist($articleLike);
        $this->doctrine->getManager()->flush();

        return $articleLike;
    }

    public function remove(ArticleLike $articleLike)
    {
        $this->doctrine->getManager()->remove($articleLike);
        $this->doctrine->getManager()->flush();

        return $articleLike;
    }
}
