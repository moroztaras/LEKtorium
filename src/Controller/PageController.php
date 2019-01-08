<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PageController.
 *
 * @Route("/")
 */
class PageController extends Controller
{
    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('article_list');
    }

    /**
     * @Route("/admin", methods={"GET"}, name="admin")
     */
    public function adminAction()
    {
        return $this->redirectToRoute('admin_article_list');
    }

//    public function preview(Request $request)
//    {
//        return $this->render('preview.html.twig');
//    }
}
