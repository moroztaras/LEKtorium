<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $text = $crawler->filter('body > form > h1')->text();
        $this->assertEquals('Please sign in', $text);
    }

    public function testLoginSendForm()
    {
        $client = static::createClient();
        $client->request(
          Request::METHOD_GET,
          '/login',
          [
            'email' => 'moroztaras@i.ua',
            'password' => 'moroztaras@i.ua',
          ]
        );
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testRegistrationOK()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/registration');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $text = $crawler->filter('body > h1')->text();
        $this->assertEquals('Registration user', $text);
    }

    public function testRegistrationNotAllowed()
    {
        $client = static::createClient();
        $client->request('PUT', '/registration');
        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
    }

    public function testPageAccessDenied()
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
}
