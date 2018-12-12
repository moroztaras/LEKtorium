<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Article;
use App\Form\ArticleType;
use App\AppEvents;
use App\Event\ArticleEvent;
use App\Services\ArticleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController.
 *
 * @Route("/admin/article")
 */
class ArticleController extends Controller
{
    /**
     * @Route("", methods={"GET"}, name="admin_article_list")
     */
    public function listAction(Request $request, ArticleService $articleService)
    {
        $articles = $articleService->list($request);

        return $this->render('admin/article/list.html.twig', [
          'articles' => $articles,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="admin_article_new")
     */
    public function newAction(Request $request, ArticleService $articleService)
    {
        $article = new Article();
        $user = $this->getUser();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleService->save($user, $article);

            $dispatcher = $this->get('event_dispatcher');
            $event = new ArticleEvent($article);
            $dispatcher->dispatch(AppEvents::ARTICLE_CREATED, $event);

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('admin/article/new.html.twig', [
          'form_article' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_article_edit", requirements={"id": "\d+"})
     */
    public function editAction($id, Request $request, ArticleService $articleService)
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $user = $this->getUser();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleService->save($user, $article);

            $dispatcher = $this->get('event_dispatcher');
            $event = new ArticleEvent($article);
            $dispatcher->dispatch(AppEvents::ARTICLE_EDIT, $event);

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('admin/article/new.html.twig', [
          'form_article' => $form->createView(),
        ]);
    }
}
