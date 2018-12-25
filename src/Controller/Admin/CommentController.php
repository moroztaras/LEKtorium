<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Services\CommentService;
use App\AppEvents;
use App\Event\CommentEvent;
use App\Form\Admin\CommentType;
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
     * @Route("/{id}/edit", name="admin_comment_edit")
     * @Method({"GET", "PUT"})
     */
    public function editAction($id, Request $request)
    {
        /** @var Comment $comment */
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->commentService->save($comment);

            $dispatcher = $this->get('event_dispatcher');
            $event = new CommentEvent($comment);
            $dispatcher->dispatch(AppEvents::COMMENT_EDIT, $event);

            return $this->redirectToRoute('admin_comment_list');
        }

        return $this->render('admin/comment/form.html.twig', [
          'form_comment' => $form->createView(),
          'title' => 'Edit comment',
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
