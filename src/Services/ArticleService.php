<?php

namespace App\Services;

use function Symfony\Component\Console\Tests\Command\createClosure;

class ArticleService
{
    public function handleArticle($name)
    {
        $faker = \Faker\Factory::create();

        $article = $faker->realText(100);

        return "Super cool article written by ".$name."<br>".$article;
    }
}
