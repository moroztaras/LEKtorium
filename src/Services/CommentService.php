<?php

namespace App\Services;

use App\Entity\Comment;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

class CommentService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $doctrine, PaginatorInterface $paginator)
    {
        $this->doctrine = $doctrine;
        $this->paginator = $paginator;
    }

    public function list($request)
    {
        return  $this->paginator->paginate(
          $this->doctrine->getRepository(Comment::class)->findBy([], ['id' => 'DESC']),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );
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

    public function remove(Comment $comment)
    {
        $this->doctrine->getManager()->remove($comment);
        $this->doctrine->getManager()->flush();

        return $comment;
    }

    protected function getArticle($id)
    {
        $article = $this->doctrine->getManager()->getRepository('App:Article')->find($id);

        return $article;
    }
}
