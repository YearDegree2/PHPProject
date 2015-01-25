<?php

namespace Http;

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
        return new self($_GET, $_POST);
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
}
