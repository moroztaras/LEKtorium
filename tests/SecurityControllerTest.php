<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginAction()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/login');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/login');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $text = $crawler->filter('body > div > h1')->text();
        $this->assertEquals('Please sign in', $text);
    }

    public function testLoginFields()
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/login');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("Email")')->count());
        $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("Password")')->count());
    }

    public function testLoginForm()
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_GET, '/login');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $text = $crawler->filter('body > div > h1')->text();
        $this->assertEquals('Please sign in', $text);

        $this->assertCount(3, $crawler->filter('input'));

        $form = $crawler->filter('button[type=submit]')->form();
        $form['email'] = 'moroztaras@i.ua';
        $form['password'] = 'moroztaras';
        $crawler = $client->submit($form);

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
}
