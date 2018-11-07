<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Services\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 *
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/", methods={"GET", "POST"}, name="index")
     */
    public function indexAction(Request $request, ArticleService $articleService)
    {
        $name = $request->query->get('name');
        $text = $articleService->handleArticle($name);

        if ($request->getMethod() === Request::METHOD_GET) {
            $article = new Article();
            $article->setTitle('Title article');
            $article->setText($text);
            $article->setAuthor('Moroz Taras');

            $this->getDoctrine()->getManager()->persist($article);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('article/index.html.twig', [
          'text' => $text,
        ]);
    }

    /**
     * @Route("/list", methods={"GET"}, name="article_list")
     */
    public function listAction()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('article/list.html.twig', [
          'articles' => $articles
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="article_new")
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute("article_list");
        }

        return $this->render('article/new.html.twig', [
          'form' => $form->createView(),
        ]);
    }
}
