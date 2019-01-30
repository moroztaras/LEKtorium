<?php

namespace App\Controller\Api\User;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\ArticleLike;
use App\Exception\JsonHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Swagger\Annotations as SWG;

class ArticleLikeController extends AbstractController
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
     * @Route("/api/article/{id}/like", methods={"GET"}, name="api_article_like")*
     * @SWG\Response(
     *     response=400,
     *     description="Invalid api token"
     * )
     */
    public function addOrRemoveLikeAction(Request $request, Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Authentication error');
        }
        /** @var ArticleLike $like */
        $like = $em->getRepository(ArticleLike::class)
            ->findOneBy([
                'user' => $user,
                'article' => $article->getId(),
            ]);
        if (!$like) {
            $like = new ArticleLike();
            $like->setUser($user)
                ->setArticle($article);
            $em->persist($like);
        } else {
            $em->remove($like);
        }
        $em->flush();

        return $this->json(['like' => $like], Response::HTTP_OK);
    }
}
