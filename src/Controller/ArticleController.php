<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Services\ArticleService;
use App\Services\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController.
 *
 * @Route("/article")
 */
class ArticleController extends Controller
{
    public $articleService;
    public $commentService;

    public function __construct(ArticleService $articleService, CommentService $commentService)
    {
        $this->articleService = $articleService;
        $this->commentService = $commentService;
    }

    /**
     * @Route("", methods={"GET"}, name="article_list")
     */
    public function listAction(Request $request)
    {
        $articles = $this->articleService->list($request);

        return $this->render('article/list.html.twig', [
          'articles' => $articles,
        ]);
    }

    /**
     * @Route("/{id}", methods={"GET", "POST"}, name="article_view", requirements={"id"})
     */
    public function viewAction(Request $request, Article $article)
    {
        $user = $this->getUser();
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();
            $this->commentService->save($user, $comment, $article);

            return $this->redirectToRoute('article_view', ['id' => $article->getId()]);
        }

        return $this->render('article/view.html.twig', [
          'article' => $article,
        ]);
    }
}
