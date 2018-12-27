<?php

namespace App\Services;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Tag;
use App\Form\Admin\Model\ArticleModel;
use App\FileAssistant;
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

    /**
     * @var FileAssistant
     */
    private $fileAssistant;

    public function __construct(ManagerRegistry $doctrine, ContainerInterface $container, PaginatorInterface $paginator, FileAssistant $fileAssistant)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->paginator = $paginator;
        $this->fileAssistant = $fileAssistant;
    }

    public function save(User $user, ArticleModel $articleModel)
    {
        $article = new Article();

        $article
          ->setTitle($articleModel->getTitle())
          ->setText($articleModel->getTitle())
          ->setUser($user);

        if($articleModel->getImage()){
            $file = $this->fileAssistant->prepareUploadFile($articleModel->getImage(), 'article/'.$this->fileAssistant->getFolderMonthYear());
            $file->setStatus(1);
            $dataImage = $article->getImage();
            if($dataImage){
                $this->doctrine->getManager()->remove($dataImage);
            }
            $article->setImage($file);//id == null,
        }

        $tags = $this->generateTags($articleModel->getTagsInput(), $article);
        if (null !== $tags) {
            foreach ($tags as $tag) {
                $this->doctrine->getManager()->persist($tag);
            }
        }
        $this->doctrine->getManager()->persist($article);
        $this->doctrine->getManager()->flush();

        return $articleModel;
    }

    public function list($request)
    {
        return  $this->paginator->paginate(
          $this->doctrine->getRepository(Article::class)->findBy([], ['id' => 'DESC']),
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
}
