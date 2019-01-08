<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Entity\User;
use App\Services\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController.
 */
class CommentController extends Controller
{
    /**
     * @var CommentService
     */
    public $commentService;

    /**
     * CommentController constructor.
     *
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function new($id)
    {
        /** @var User $user */
        $user = $this->getUser();
        $comment = $this->commentService->new($id, $user);

        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('comment/form.html.twig',
        [
          'comment' => $comment,
          'form_comment' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/{id}", name="comment_create")
     */
    public function createAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->getUser();
        $comment = $this->commentService->new($id, $user);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->commentService->save($comment);

            return $this->redirect($this->generateUrl('article_view',
              [
                  'id' => $comment->getArticle()->getId(),
              ]).'#comment-'.$comment->getId()
            );
        }

        return $this->redirectToRoute('article_view', ['id' => $id]);
    }
}
