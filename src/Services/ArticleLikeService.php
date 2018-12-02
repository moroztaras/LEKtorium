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
        $likeRepo = $this->doctrine->getRepository(ArticleLike::class);
        $like = $likeRepo->findLikeArticle($user, $article);

        if ($like) {
            return $likeRepo->removeLikeArticle($user, $article);
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
}
