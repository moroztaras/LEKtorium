<?php

namespace App\Controller;

use App\Entity\Article;
use App\Services\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{
    /**
     * @Route("/", methods={"GET","POST"})
     */
    public function indexAction(Request $request, ArticleService $articleService)
    {
        $name = $request->query->get('name');
        $text=$articleService->handleArticle($name);

        if($request->getMethod()==Request::METHOD_GET){
            $article = new Article();

            $article->setText($text);

            $this->getDoctrine()->getManager()->persist($article);//зверни увагу
            $this->getDoctrine()->getManager()->flush();//запиши в базу

        }
        return new Response("<html><body><h1>".$article->getText()."</h1></body></html>");
    }

    /**
     * @Route("/show", methods={"GET"})
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
}
