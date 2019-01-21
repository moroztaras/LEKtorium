<?php

namespace App\Controller\Api\User;

use App\Entity\Article;
use App\Entity\User;
use App\Exception\JsonHttpException;
use App\Exception\NotFoundException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;

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

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, RouterInterface $router, PaginatorInterface $paginator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->router = $router;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/page={page}", name="api_articles_list")
     * @Method({"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns article object array"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Page not found"
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="path",
     *     type="integer",
     *     description="Articles page"
     * )
     * @SWG\Tag(name="Articles list API")
     */
    public function listArticle(Request $request, string $page, $limit = 5)
    {
        return $this->json([
          'articles' => $this->paginator->paginate(
            $this->getDoctrine()->getRepository(Article::class)->getListArticles(),
            $request->query->getInt('page', $page), $limit),
        ],
          Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_articles_show")
     * @Method({"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns article object"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Article not found"
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Article ID"
     * )
     * @SWG\Tag(name="Article show API")
     */
    public function showArticle(Article $article)
    {
        if (!$article) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Not Found.');
        }

        return $this->json(['article' => $article], Response::HTTP_OK);
    }

    /**
     * @Route("/api/articles", name="api_articles_add", methods={"POST"})
     *
     * @throws \Exception
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns created comment object"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Invalid api token"
     * )
     * @SWG\Parameter(
     *     name="comment",
     *     in="body",
     *     type="json",
     *     description="Article object used for create article",
     *     @SWG\Schema(
     *            type="object",
     *            @SWG\Property(property="title", type="string", example="New title for new articles"),
     *            @SWG\Property(property="text", type="string", example="New fake text for new articles"),
     *            @SWG\Property(property="imageName", type="string", example="article_image.jpg"),
     *         )
     * )
     * @SWG\Tag(name="Article New API")
     *
     * @Security(name="ApiAuth")
     */
    public function addArticleAction(Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)
          ->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(400, 'Authentication error');
        }
        /* @var Article $article */
        $article = $this->serializer->deserialize($request->getContent(), Article::class, 'json');
        $article->setUser($user)
        //  ->setArticle($article)
          ->setCreatedAt(new \DateTime())
          ->setApproved(true);

        $errors = $this->validator->validate($article);
        if (count($errors)) {
            throw new JsonHttpException(400, (string) $errors->get(0)->getPropertyPath().': '.(string) $errors->get(0)->getMessage());
        }
        $this->getDoctrine()->getManager()->persist($article);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['article' => $article]);
    }
}
