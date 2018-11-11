<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $text = $crawler->filter('body > form > h1')->text();
        $this->assertEquals('Please sign in', $text);
    }

    public function testPageAccessDenied()
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testPageNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/about');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
