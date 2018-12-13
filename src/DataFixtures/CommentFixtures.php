<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $articleRepo = $manager->getRepository(Article::class);
        $articles = $articleRepo->findAll();
        foreach ($articles as $article) {
            for ($i = 1; $i <= 3; ++$i) {
                $comment = new Comment();
                $comment->setComment('Comment '.$i.' > '.$article->getTitle());
                $article->addComment($comment);
            }
            $manager->persist($article);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ArticleFixtures::class,
        ];
    }
}
