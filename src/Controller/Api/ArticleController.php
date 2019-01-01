<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("api/article")
 */
class ArticleController extends Controller
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("", name="api_article_list")
     * @Method({"GET"})
     */
    public function listArticle()
    {
        return $this->json(
        [
          'articles' => $this->getDoctrine()->getRepository(Article::class)->findAll(),
        ],
          Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_article_show")
     * @Method({"GET"})
     */
    public function showArticle(Article $article)
    {
        if (!$article) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Article Not Found.');
        }

        return $this->json(['article' => $article], Response::HTTP_OK);
    }

    /**
     * @Route("", name="api_article_new")
     * @Method({"POST"})
     */
    public function newArticle(Request $request)
    {
        $json = $request->getContent();

        $article = $this->serializer->deserialize($json, Article::class, JsonEncoder::FORMAT);

        return $this->json(['article' => $article]);
    }

    /**
     * @Route("/{id}", name="api_article_delete", requirements={"id": "\d+"})
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $this->getDoctrine()->getManager()->remove($article);
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
          'success' => [
            'code' => 200,
            'message' => 'Article was deleted',
          ],
        ], Response::HTTP_OK);
    }
}
