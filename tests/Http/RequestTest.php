<?php

namespace Http;

class RequestTest extends \TestCase
{
    private $requestEmpty;
    private $requestNotEmpty;

    public function setUp()
    {
        $this->requestEmpty = new Request();
        $this->requestNotEmpty = new Request(array(
            'champ'  => 'value',
        ));
    }

    public function testGetMethodWithoutMethod()
    {
        $this->assertEquals("GET", $this->requestEmpty->getMethod());
    }

    public function testGetMethodWithMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals("POST", $this->requestNotEmpty->getMethod());
    }

    public function testGetUriWithoutUri()
    {
        $this->assertEquals("/", $this->requestEmpty->getUri());
    }

    public function testGetUriWithUri()
    {
        $_SERVER['REQUEST_URI'] = '/test';
        $this->assertEquals("/test", $this->requestNotEmpty->getUri());
    }

    public function testGetParameterRequestEmpty()
    {
        $this->assertNull($this->requestEmpty->getParameter("champ"));
    }

    public function testGetParameterRequestEmptyAndDefaultNotNull()
    {
        $this->assertEquals("default", $this->requestEmpty->getParameter("champ", "default"));
    }

    public function testGetParameterRequestNotEmpty()
    {
        $this->assertEquals("value", $this->requestNotEmpty->getParameter("champ"));
    }

    public function testGuessBestFormatWithoutFormat()
    {
        $this->assertEquals("html", $this->requestEmpty->guessBestFormat());
    }

    public function testGuessBestFormatWithJson()
    {
        $_SERVER['HTTP_ACCEPT'] = 'application/json';
        $this->assertEquals("json", $this->requestNotEmpty->guessBestFormat());
    }

    public function testGuessBestFormatWithXml()
    {
        $_SERVER['HTTP_ACCEPT'] = 'application/xml';
        $this->assertEquals("xml", $this->requestNotEmpty->guessBestFormat());
    }
}
