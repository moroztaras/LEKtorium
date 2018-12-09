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

    public function preview(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('preview.html.twig');
    }
}
