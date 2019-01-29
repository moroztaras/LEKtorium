<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Entity\User;
use App\Services\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

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
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * CommentController constructor.
     *
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService, FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
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
     * @Route("/comment/{id}", name="comment_create", requirements={"id": "\d+"})
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
            $this->flashBag->add('success', 'The comment has been sent to moderation and will be published after checking.');

            return $this->redirect($this->generateUrl('article_view',
              [
                  'id' => $comment->getArticle()->getId(),
              ]).'#comment-'.$comment->getId()
            );
        }

        return $this->redirectToRoute('article_view', ['id' => $id]);
    }
}
