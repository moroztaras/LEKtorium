<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testRegistrationFields()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/registration');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("Email")')->count());
        $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("Name")')->count());
        $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("Password")')->count());
        $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("Repeat password")')->count());
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
