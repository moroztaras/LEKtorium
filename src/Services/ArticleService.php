<?php

namespace App\Services;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Tag;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ArticleService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $doctrine, ContainerInterface $container, PaginatorInterface $paginator)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->paginator = $paginator;
    }

    public function save(User $user, Article $article)
    {
        $article
          ->setTitle($article->getTitle())
          ->setText($article->getTitle())
          ->setUser($user);

        $tags = $this->generateTags($article->getTagsInput(), $article);
        if (null !== $tags) {
            foreach ($tags as $tag) {
                $this->doctrine->getManager()->persist($tag);
            }
        }
        $this->doctrine->getManager()->persist($article);
        $this->doctrine->getManager()->flush();

        return $article;
    }

    public function list($request)
    {
        return  $this->paginator->paginate(
          $this->doctrine->getRepository(Article::class)->getListArticles(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );
    }

    public function addReviewForArticle(Article $article)
    {
        $article->setReviews($article->getReviews() + 1);
        $this->doctrine->getManager()->persist($article);
        $this->doctrine->getManager()->flush();

        return $this;
    }

    public function adminList($request)
    {
        return  $this->paginator->paginate(
          $this->doctrine->getRepository(Article::class)->getAdminListArticles(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );
    }

    public function remove(Article $article)
    {
        $this->doctrine->getManager()->remove($article);
        $this->doctrine->getManager()->flush();

        return $article;
    }

    public function generateTags(?string $tagsInput, Article $article)
    {
        $tagsInput = trim($tagsInput);
        $tagsInput = explode(', ', $tagsInput);
        $tagsInput = array_unique($tagsInput);
        $tags = [];
        foreach ($tagsInput as $tagName) {
            $tagName = trim($tagName);
            if ('' == $tagName) {
                continue;
            }
            $tag = new Tag();
            $tag->setName($tagName)
              ->setArticle($article);
            $tags[] = $tag;
        }

        return $tags;
    }

    public function countAdminListArticles()
    {
        return count($this->doctrine->getRepository(Article::class)->getAdminListArticles());
    }

    public function countPublishedArticles()
    {
        return count($this->doctrine->getRepository(Article::class)->getListArticles());
    }
}
