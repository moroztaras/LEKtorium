<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleLike;
use App\Services\ArticleLikeService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class ArticleLikeController.
 */
class ArticleLikeController extends Controller
{
    /**
     * @var ArticleLikeService
     */
    private $articleLikeService;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * ArticleLikeController constructor.
     */
    public function __construct(ArticleLikeService $articleLikeService, FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
        $this->articleLikeService = $articleLikeService;
    }

    /**
     * @Route("/article/{id}/like", methods={"GET","POST"}, name="article_like_add")
     */
    public function addAction(Request $request, Article $article)
    {
        $referer = $request->headers->get('referer');
        $articleLike = new ArticleLike();
        $user = $this->getUser();

        if ($user) {
            $this->articleLikeService->status($user, $article, $articleLike);

            return $this->redirect($referer);
        } else {
            $this->flashBag->add('error', 'User is not logged in');

            return $this->redirectToRoute('app_login');
        }
    }
}
