<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleControllerTest extends WebTestCase
{
    public function testLoginFields()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/article/new');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("Title")')->count());
        $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("Text")')->count());
        $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("Author")')->count());
    }

    public function testArticleListNotAllowed()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/article/list');
        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
    }

    public function testArticleNewGet()
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/article/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $text = $crawler->filter('body > h1')->text();
        $this->assertEquals('Create new article', $text);
    }

    public function testArticleNewPostOk()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/article/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testArticleNewPostSendForm()
    {
        $client = static::createClient();
        $client->request(
          Request::METHOD_POST,
          '/article/new',
          [
            'title' => 'Title for article',
            'text' => 'Text for article',
            'author' => 'Moroz Taras',
          ]
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testArticleNewNotAllowed()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, '/article/new');
        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
    }

    public function testPageNotFound()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/about');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
