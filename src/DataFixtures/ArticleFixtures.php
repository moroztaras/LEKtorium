<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $article = new Article();
        $article->setTitle('Doctrine Fixtures Bundle');
        $article->setText('Fixtures are used to load a "fake" set of data into a database that can then be used for testing or to help give you some interesting data while you\'re developing your application.');
        $article->setAuthor('Moroz Taras');

        $manager->persist($article);
        $manager->flush();
    }
}
