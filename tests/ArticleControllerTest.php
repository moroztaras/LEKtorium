<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function testArticleNew()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/article/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $text = $crawler->filter('body > h1')->text();
        $this->assertEquals('Create new article', $text);
    }
}
