<?php

namespace App\Controller\Api\User;

use App\Entity\Comment;
use App\Entity\Article;
use App\Exception\JsonHttpException;
use App\Entity\User;
use App\Exception\NotFoundException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * Class CommentController.
 *
 * @Route("api/comments")
 */
class CommentController extends AbstractController
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
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, PaginatorInterface $paginator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/page={page}", name="api_comment_list")
     * @Method({"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns comment object array"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Page not found"
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="path",
     *     type="integer",
     *     description="Comments page"
     * )
     * @SWG\Tag(name="Comments list API")
     */
    public function listComment(Request $request, string $page, $limit = 5)
    {
        return $this->json([
          'comments' => $this->paginator->paginate(
            $this->getDoctrine()->getRepository(Comment::class)->findAll(),
            $request->query->getInt('page', $page), $limit),
        ],
          Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_comment_show")
     * @Method({"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns comment object"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Comment not found"
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Comment ID"
     * )
     * @SWG\Tag(name="Comment show API")
     */
    public function showComment(Comment $comment)
    {
        if (!$comment) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Not Found.');
        }

        return $this->json(['comment' => $comment], Response::HTTP_OK);
    }

    /**
     * @Route("/{id_article}/add", methods={"POST"}, name="api_comment_add")
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
     *     name="article",
     *     in="path",
     *     type="integer",
     *     description="Article ID which in comment will be add"
     * )
     * @SWG\Parameter(
     *     name="comment",
     *     in="body",
     *     type="json",
     *     description="Comment object used for create comment",
     *     @SWG\Schema(
     *            type="object",
     *            @SWG\Property(property="comment", type="string"),
     *         )
     * )
     * @SWG\Tag(name="Comment Add API")
     *
     * @Security(name="ApiAuth")
     */
    public function addCommentAction(Request $request, Article $article)
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
        /* @var Comment $comment */
        $comment = $this->serializer->deserialize($request->getContent(), Comment::class, 'json');
        $comment->setUser($user)
          ->setArticle($article)
          ->setCreatedAt(new \DateTime())
          ->setApproved(true);

        $errors = $this->validator->validate($comment);
        if (count($errors)) {
            throw new JsonHttpException(400, (string) $errors->get(0)->getPropertyPath().': '.(string) $errors->get(0)->getMessage());
        }
        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['comment' => $comment]);
    }
}