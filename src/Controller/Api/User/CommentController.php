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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @Route("/page={page}", name="api_comments_list", methods={"GET"}, requirements={"page": "\d+"})
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
     * @Route("/{id}", name="api_comments_show", methods={"GET"}, requirements={"id": "\d+"} )
     */
    public function showComment(Comment $comment)
    {
        if (!$comment) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Not Found.');
        }

        return $this->json(['comment' => $comment], Response::HTTP_OK);
    }

    /**
     * @Route("/{article}/add", name="api_comments_add", methods={"POST"}, requirements={"article": "\d+"})
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

    /**
     * @Route("/{id}", name="api_comments_delete", methods={"DELETE"}, requirements={"id": "\d+"})
     */
    public function removeComment(Request $request, Comment $comment)
    {
        if (!$comment) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Not Found.');
        }
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)
          ->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Authentication error');
        }

        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
          'success' => [
            'code' => Response::HTTP_OK,
            'message' => 'Comment was deleted',
          ],
        ], Response::HTTP_OK);
    }
}
