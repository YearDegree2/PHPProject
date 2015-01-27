<?php

namespace Http;

use Negotiation\Negotiator;

class Request
{
    private $parameters = array();

    const GET    = 'GET';

    const POST   = 'POST';

    const PUT    = 'PUT';

    const DELETE = 'DELETE';

    public function __construct(array $query = array(), array $request = array())
    {
        $this->parameters = array_merge($query, $request);
    }

    public static function createFromGlobals()
    {
        $request = $_POST;
        if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            if ('application/json' === $_SERVER['HTTP_CONTENT_TYPE']) {
                $data    = file_get_contents('php://input');
                $request = @json_decode($data, true);
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            if ('application/json' === $_SERVER['CONTENT_TYPE']) {
                $data = file_get_contents('php://input');
                $request = @json_decode($data, true);
            }
        }

        return new self($_GET, $request);
    }

    public function getMethod()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : self::GET;
        if (self::POST === $method) {
            return $this->getParameter('_method', $method);
        }

        return $method;
    }

    public function getUri()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return $uri;
    }

    public function getParameter($name, $default = null)
    {
        if (array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }

        return $default;
    }

    public function guessBestFormat()
    {
        $negotiator = new Negotiator();
        $acceptHeader = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : "text/html";
        $priorities   = array('html', 'application/json', 'application/xml', '*/*');

        return $negotiator->getBestFormat($acceptHeader, $priorities);
    }
}
