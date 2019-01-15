<?php

namespace App\Controller\Api\User;

use App\Entity\Article;
use App\Exception\JsonHttpException;
use App\Exception\NotFoundException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ArticleController.
 *
 * @Route("api/articles")
 */
class ArticleController extends Controller
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, RouterInterface $router)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->router = $router;
    }

    /**
     * @Route("", name="api_articles_list")
     * @Method({"GET"})
     */
    public function listArticle()
    {
        return $this->json(
        [
          'articles' => $this->getDoctrine()->getRepository(Article::class)->getListArticles(),
        ],
          Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_articles_show")
     * @Method({"GET"})
     */
    public function showArticle(Article $article)
    {
        if (!$article) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Not Found.');
        }

        return $this->json($article, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="api_articles_new")
     * @Method({"POST"})
     */
    public function newArticleAction(Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }
        /** @var Article $article */
        $article = $this->serializer->deserialize($request->getContent(), Article::class, JsonEncoder::FORMAT);

        $errors = $this->validator->validate($article);

        if (count($errors)) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }

        $this->getDoctrine()->getManager()->persist($article);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($article);
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
