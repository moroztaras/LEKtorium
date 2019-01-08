<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Services\CommentService;
use App\Security\CommentVoter;
//use App\AppEvents;
//use App\Event\CommentEvent;
use App\Form\Admin\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class CommentController.
 *
 * @Route("/admin/comment")
 */
class CommentController extends Controller
{
    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(CommentService $commentService, FlashBagInterface $flashBag)
    {
        $this->commentService = $commentService;
        $this->flashBag = $flashBag;
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
        if (!$comment) {
            $this->flashBag->add('error', 'Comment not found');

            return $this->redirectToRoute('admin_comment_list');
        }
        $this->denyAccessUnlessGranted(CommentVoter::EDIT, $comment);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);

//            $dispatcher = $this->get('event_dispatcher');
//            $event = new CommentEvent($comment);
//            $dispatcher->dispatch(AppEvents::COMMENT_EDIT, $event);
            $this->flashBag->add('success', 'Comment was edited');

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
        if (!$comment) {
            $this->flashBag->add('error', 'Comment not found');

            return $this->redirectToRoute('admin_comment_list');
        }
        $this->denyAccessUnlessGranted(CommentVoter::EDIT, $comment);
        $this->commentService->remove($comment);

//        $dispatcher = $this->get('event_dispatcher');
//        $event = new CommentEvent($comment);
//        $dispatcher->dispatch(AppEvents::COMMENT_DELETE, $event);
        $this->flashBag->add('error', 'Comment was deleted');

        return $this->redirect($referer);
    }
}
