<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Services\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('article/article.html.twig', [
          'text' => $text,
        ]);
    }

    /**
     * @Route("/article/show", methods={"GET"}, name="show")
     */
    public function showAction()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('article/articles.html.twig', [
          'articles' => $articles
        ]);
    }

    /**
     * @param Request $request
     * @Route("/article/new", methods={"GET", "POST"}, name="new_article")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newArticleAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute("show");
        }

        return $this->render('article/new.html.twig', [
          'form' => $form->createView(),
        ]);
    }
}
