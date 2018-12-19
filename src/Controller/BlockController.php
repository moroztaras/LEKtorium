<?php

namespace App\Controller;

use App\Services\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

    public function user()
    {
        $user = $this->getUser();

        return $this->render('block/user.html.twig', [
          'user' => $user,
        ]);
    }

    /**
     * @Route("/tag_list", methods={"GET"}, name="tag_lsit")
     */
    public function sidebar(TagService $tagService)
    {
        $tags = $tagService->list();

        return $this->render('tag/sidebar_list.html.twig', [
          'tags' => $tags,
        ]);
    }
}
