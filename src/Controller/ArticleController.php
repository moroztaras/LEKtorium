<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Services\ArticleService;
use App\AppEvents;
use App\Event\ArticleEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController.
 *
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/", methods={"GET", "POST"}, name="article_index")
     */
    public function indexAction(Request $request, ArticleService $articleService)
    {
        $name = $request->query->get('name');
        $text = $articleService->handleArticle($name);

        if (Request::METHOD_GET === $request->getMethod()) {
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
          'articles' => $articles,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="article_new")
     */
    public function newAction(Request $request, ArticleService $articleService)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleService->save($article);

            $dispatcher = $this->get('event_dispatcher');
            $event = new ArticleEvent($article);
            $dispatcher->dispatch(AppEvents::ARTICLE_CREATED, $event);

            return $this->redirectToRoute('article_list');
        }

        return $this->render('article/new.html.twig', [
          'form_article' => $form->createView(),
        ]);
    }
}
