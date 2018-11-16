<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('article_list');
    }
}
