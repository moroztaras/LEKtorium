<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TagController.
 *
 * @Route("/tag")
 */
class TagController extends Controller
{
    /**
     * @Route("/{name}", methods={"GET","POST"}, name="list_of_articles_by_tag")
     */
    public function listOfArticlesByTagAction($name, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if (null !== $name) {
            $tags = $em->getRepository(Tag::class)->findBy(['name' => $name]);
        } else {
            throw $this->createNotFoundException('The tag does not exist.');
        }

        return $this->render('tag/index.html.twig', [
          'tag_name' => $name,
          'tags' => $tags,
        ]);
    }
}
