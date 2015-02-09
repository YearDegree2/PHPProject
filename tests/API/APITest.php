<?php

use Goutte\Client;

class APITest extends TestCase
{
    private $client;
    private $endPoint;

    public function setUp()
    {
        $this->client = new Client();
        $this->endPoint = "http://33.33.33.10:82";
    }

    public function testGetHome()
    {
        $this->client->request('GET', sprintf('%s/', $this->endPoint));
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('text/html', $response->getHeader('Content-Type'));

    }

    public function testGetStatuses()
    {
        $this->client->request('GET', sprintf('%s/statuses', $this->endPoint));
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('text/html', $response->getHeader('Content-Type'));
    }

    public function testGetStatusByIdExisting()
    {
        $this->client->request('GET', sprintf('%s/statuses/1', $this->endPoint));
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('text/html', $response->getHeader('Content-Type'));
    }

    public function testGetStatusByIdNonExisting()
    {
        $this->client->request('GET', sprintf('%s/statuses/0', $this->endPoint));
        $response = $this->client->getResponse();

        $this->assertEquals(404, $response->getStatus());
    }

    public function testGetStatusesByUserExisting()
    {
        $this->client->request('GET', sprintf('%s/statuses/Nico', $this->endPoint));
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('text/html', $response->getHeader('Content-Type'));
    }

    public function testGetStatusesByUserNonExisting()
    {
        $this->client->request('GET', sprintf('%s/statuses/RienDusdTout', $this->endPoint));
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatus()); // redirection sur /statuses
    }

    public function testSignin()
    {
        $this->client->request('GET', sprintf('%s/signin', $this->endPoint));

        $response = $this->client->getResponse();
        $this->assertEquals(200,$response->getStatus());
    }

    public function testLogin()
    {
        $this->client->request('GET', sprintf('%s/login', $this->endPoint));

        $response = $this->client->getResponse();
        $this->assertEquals(200,$response->getStatus());
    }
}
