<?php

namespace App\Services;

class ArticleService
{
    public function handleArticle($name)
    {
        $faker = \Faker\Factory::create();

        $article = $faker->realText(100);

        return 'Super cool article written by '.$name.' '.$article;
    }
}
