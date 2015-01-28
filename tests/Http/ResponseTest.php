<?php

namespace Http;

class ResponseTest extends \TestCase
{
    private $response;

    public function setUp()
    {
        $this->response = new Response("content");
    }

    public function testGetStatusCode()
    {
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function testGetContent()
    {
        $this->assertEquals("content", $this->response->getContent());
    }
}
