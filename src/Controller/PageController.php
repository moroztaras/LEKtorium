<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

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

    public function user()
    {
        $user = $this->getUser();

        return $this->render('page/user.html.twig', [
          'user' => $user,
        ]);
    }
}
