<?php

namespace App\Controller;

use App\Entity\Article;
use App\Services\ArticleService;
use App\Services\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class ArticleController.
 *
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @var ArticleService
     */
    public $articleService;

    /**
     * @var CommentService
     */
    public $commentService;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(ArticleService $articleService, CommentService $commentService, FlashBagInterface $flashBag)
    {
        $this->articleService = $articleService;
        $this->commentService = $commentService;
        $this->flashBag = $flashBag;
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
    public function viewAction(Article $article)
    {
        if (!$article) {
            $this->flashBag->add('error', 'Article not found');

            return $this->redirectToRoute('article_list');
        }
        $this->articleService->addReviewForArticle($article);

        $comments = $this->commentService->getCommentsForArticle($article);

        return $this->render('article/view.html.twig', [
          'article' => $article,
          'comments' => $comments,
        ]);
    }
}
