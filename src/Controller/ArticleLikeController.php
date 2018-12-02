<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleLike;
use App\Services\ArticleLikeService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleLikeController.
 */
class ArticleLikeController extends Controller
{
    /**
     * @Route("/article/{id}/like", methods={"GET","POST"}, name="article_like_add")
     */
    public function addAction(Request $request, Article $article, ArticleLikeService $articleLikeService)
    {
        $referer = $request->headers->get('referer');
        $articleLike = new ArticleLike();
        $user = $this->getUser();

        if ($user) {
            $articleLikeService->status($user, $article, $articleLike);

            return $this->redirect($referer);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
