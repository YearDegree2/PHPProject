<?php

namespace Http;

class Request
{
    const GET    = 'GET';

    const POST   = 'POST';

    const PUT    = 'PUT';

    const DELETE = 'DELETE';

    public static function createFromGlobals()
    {
        return new self();
    }

    public function getMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : self::GET;
    }

    public function getUri()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return $uri;
    }
}
