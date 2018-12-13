<?php

namespace App\Controller\Api;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @Route("api/article/{article}")
     */
    public function showArticle(Article $article)
    {
        return $this->json(['article' => $article]);
    }

    /**
     * @Route("api/article", methods={"POST"})
     */
    public function createArticle(Request $request)
    {
        $json = $request->getContent();
        $article = $this->serializer->deserialize($json, Article::class, JsonEncoder::FORMAT);
        // TODO validate article
        return $this->json(['article' => $article]);
    }
}
