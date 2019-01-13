<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Services\ArticleService;
use App\Services\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlockController.
 */
class BlockController extends Controller
{
    public function logo()
    {
        return $this->render('block/logo.html.twig');
    }

    public function mainMenu()
    {
        return $this->render('block/main-menu.html.twig');
    }

    public function mainMenuFooter()
    {
        return $this->render('block/main-menu-footer.html.twig');
    }

    public function category()
    {
        return $this->render('block/category-list.html.twig');
    }

    public function adminMenu()
    {
        return $this->render('block/admin-category-list.html.twig');
    }

    public function user()
    {
        $user = $this->getUser();

        return $this->render('block/user.html.twig', [
          'user' => $user,
        ]);
    }

    public function sidebarTag(TagService $tagService)
    {
        $tags = $tagService->list();

        return $this->render('tag/sidebar_list.html.twig', [
          'tags' => $tags,
        ]);
    }

    public function sidebarSearch()
    {
        return $this->render('block/search.html.twig');
    }

    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $articles = null;
        $count_articles = 0;

        if ($request->query->get('search')) {
            $data = $request->query->get('search');
            $articles = $this->getDoctrine()->getRepository('App:Article')->findByWord($data);
            $count_articles = count($articles);
            unset($request);
        }

        return $this->render(
          'article/search.html.twig', [
          'articles' => $articles,
          'count_articles' => $count_articles,
        ]);
    }

    public function sidebarLatestComments()
    {
        $limit = 5;
        $comments = $this->getDoctrine()->getRepository(Comment::class)->getLatestComments($limit);

        return $this->render(
          'block/latest_comments.html.twig', [
          'comments' => $comments,
        ]);
    }
}
