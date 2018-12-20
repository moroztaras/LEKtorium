<?php

namespace App\Event;

use App\Entity\Article;
use Symfony\Component\EventDispatcher\Event;

class ArticleEvent extends Event
{
    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function getArticle()
    {
        return $this->article;
    }
}
