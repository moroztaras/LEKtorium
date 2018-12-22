<?php

namespace App\Controller;

use App\Entity\Article;
use App\Services\ArticleService;
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

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
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
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('article/view.html.twig', [
          'article' => $article,
        ]);
    }
}
