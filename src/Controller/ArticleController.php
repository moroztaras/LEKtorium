<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Services\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{
    /**
     * @Route("/", methods={"GET","POST"}, name="index")
     */
    public function indexAction(Request $request, ArticleService $articleService)
    {
        $name = $request->query->get('name');
        $text=$articleService->handleArticle($name);

        if($request->getMethod()==Request::METHOD_GET){
            $article = new Article();

            $article->setTitle('Title article');
            $article->setText($text);
            $article->setAuthor('Moroz Taras');

            $this->getDoctrine()->getManager()->persist($article);
            $this->getDoctrine()->getManager()->flush();
        }

        return new Response("<html><body><h1>".$article->getText()."</h1></body></html>");
    }

    /**
     * @Route("/show", methods={"GET"}, name="show")
     */
    public function showAction()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        $html = '';
        /* @var Article $article */
        foreach ($articles as $article) {
            $html .= "<li>{$article->getText()}</li>";
        }
        return new Response(
          "<html><body><ul>$html</ul></body></html>"
        );
    }

    /**
     * @Route("/new", name="new_article")
     */
    public function newAction(Request $request)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->persist($article);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("show");
        }

        return $this->render('Article/new.html.twig',[
          'new_article_form' => $form->createView()
        ]);
    }
}
