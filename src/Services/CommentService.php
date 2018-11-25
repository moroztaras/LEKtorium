<?php

namespace App\Services;

use App\Entity\Comment;
use App\Entity\Article;
use Doctrine\Common\Persistence\ManagerRegistry;

class CommentService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function save(Comment $comment, Article $article)
    {
        $comment->setArticle($article);
        $this->doctrine->getManager()->persist($comment);
        $this->doctrine->getManager()->flush();

        return $comment;
    }
}
