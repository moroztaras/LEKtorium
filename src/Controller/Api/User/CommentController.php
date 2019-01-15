<?php

namespace App\Controller\Api\User;

use App\Entity\Comment;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("", name="api_comment_list")
     * @Method({"GET"})
     */
    public function listComment()
    {
        return $this->json(
        [
          'comments' => $this->getDoctrine()->getRepository(Comment::class)->findAll(),
        ],
          Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_comment_show")
     * @Method({"GET"})
     */
    public function showComment(Comment $comment)
    {
        if (!$comment) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Not Found.');
        }

        return $this->json($comment, Response::HTTP_OK);
    }
}
