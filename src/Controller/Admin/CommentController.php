<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Services\CommentService;
use App\AppEvents;
use App\Event\CommentEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController.
 *
 * @Route("/admin/comment")
 */
class CommentController extends Controller
{
    /**
     * @var CommentService
     */
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @Route("", methods={"GET"}, name="admin_comment_list")
     */
    public function listAction(Request $request)
    {
        $comments = $this->commentService->list($request);

        return $this->render('admin/comment/list.html.twig', [
          'comments' => $comments,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="admin_comment_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $referer = $request->headers->get('referer');
        $this->commentService->remove($comment);

        $dispatcher = $this->get('event_dispatcher');
        $event = new CommentEvent($comment);
        $dispatcher->dispatch(AppEvents::COMMENT_DELETE, $event);

        return $this->redirect($referer);
    }
}
