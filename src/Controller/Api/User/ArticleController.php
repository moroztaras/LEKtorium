<?php

namespace App\Controller\Api\User;

use App\Entity\Article;
use App\Entity\User;
use App\Services\CommentService;
use App\Exception\JsonHttpException;
use App\Exception\NotFoundException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
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

    /**
     * @var CommentService
     */
    public $commentService;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(SerializerInterface $serializer, CommentService $commentService, ValidatorInterface $validator, RouterInterface $router, PaginatorInterface $paginator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->router = $router;
        $this->paginator = $paginator;
        $this->commentService = $commentService;
    }

    /**
     * @Route("/page={page}", name="api_articles_list", methods={"GET"})
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
     * @Route("/{id}", name="api_articles_show", methods={"GET"})
     */
    public function showArticle(Article $article)
    {
        if (!$article) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Not Found.');
        }

        return $this->json(['article' => $article], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/comments", name="api_articles_show_comments_all", methods={"GET"})
     */
    public function showArticleAllComments(Article $article)
    {
        if (!$article) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Not Found.');
        }
        $comments = $this->commentService->getCommentsForArticle($article);

        return $this->json(['comments' => $comments], Response::HTTP_OK);
    }

    /**
     * @Route("", name="api_articles_add", methods={"POST"})
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
