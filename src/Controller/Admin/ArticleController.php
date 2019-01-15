<?php

namespace App\Controller\Admin;

use App\Security\ArticleVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Article;
use App\Form\Admin\ArticleType;
use App\Form\Admin\ArticleEditType;
//use App\AppEvents;
//use App\Event\ArticleEvent;
use App\Services\ArticleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class ArticleController.
 *
 * @Route("/admin/article")
 */
class ArticleController extends Controller
{
    /**
     * @var ArticleService
     */
    public $articleService;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(ArticleService $articleService, FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
        $this->articleService = $articleService;
    }

    /**
     * @Route("", methods={"GET"}, name="admin_article_list")
     */
    public function listAction(Request $request)
    {
        $articles = $this->articleService->adminList($request);
        $count_articles = $this->articleService->countAdminListArticles();
        $count_published_articles = $this->articleService->countPublishedArticles();

        return $this->render('admin/article/list.html.twig', [
          'articles' => $articles,
          'count_articles' => $count_articles,
          'count_published_articles' => $count_published_articles,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="admin_article_new")
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $user = $this->getUser();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->save($user, $form->getData());

//            $dispatcher = $this->get('event_dispatcher');
//            $event = new ArticleEvent($article);
//            $dispatcher->dispatch(AppEvents::ARTICLE_CREATED, $event);
            $this->flashBag->add('success', 'New article was created: '.$article->getTitle());

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('admin/article/form.html.twig', [
          'form_article' => $form->createView(),
          'title' => 'Create new article',
        ]);
    }

    /**
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="admin_article_edit", requirements={"id": "\d+"})
     */
    public function editAction($id, Request $request)
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if (!$article) {
            $this->flashBag->add('error', 'Article not found');

            return $this->redirectToRoute('admin_article_list');
        }
        $this->denyAccessUnlessGranted(ArticleVoter::EDIT, $article);
        $user = $this->getUser();
        $form = $this->createForm(ArticleEditType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->save($user, $article);

//            $dispatcher = $this->get('event_dispatcher');
//            $event = new ArticleEvent($article);
//            $dispatcher->dispatch(AppEvents::ARTICLE_EDIT, $event);
            $this->flashBag->add('success', 'Article was edited: '.$article->getTitle());

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('admin/article/form.html.twig', [
          'form_article' => $form->createView(),
          'title' => 'Edit article',
        ]);
    }

    /**
     * @Route("/{id}/delete", methods={"GET", "POST"}, name="admin_article_delete", requirements={"id": "\d+"})
     */
    public function deleteAction($id)
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if (!$article) {
            $this->flashBag->add('error', 'Article not found');

            return $this->redirectToRoute('admin_article_list');
        }
        $this->denyAccessUnlessGranted(ArticleVoter::EDIT, $article);
        $this->articleService->remove($article);

//        $dispatcher = $this->get('event_dispatcher');
//        $event = new ArticleEvent($article);
//        $dispatcher->dispatch(AppEvents::ARTICLE_DELETE, $event);
        $this->flashBag->add('error', 'Article was deleted: '.$article->getTitle());

        return $this->redirectToRoute('admin_article_list');
    }
}
