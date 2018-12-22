<?php

namespace App\Services;

use App\Entity\Comment;
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

    public function new($id, $user)
    {
        $comment = new Comment();
        $article = $this->getArticle($id);
        $comment->setArticle($article);
        $comment->setUser($user);

        return $comment;
    }

    public function save(Comment $comment)
    {
        $this->doctrine->getManager()->persist($comment);
        $this->doctrine->getManager()->flush();

        return $comment;
    }

    protected function getArticle($id)
    {
        $article = $this->doctrine->getManager()->getRepository('App:Article')->find($id);

        return $article;
    }
}
