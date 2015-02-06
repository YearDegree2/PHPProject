<?php

namespace Model;

class Status
{
    private $message;
    private $id;
    private $username;
    private $date;
    private $clientUsed;

    public function __construct($message, $id, $username, $date, $clientUsed = "PC")
    {
        $this->message      = $message;
        $this->id           = $id;
        $this->username     = $username;
        $this->date         = $date;
        $this->clientUsed   = $clientUsed;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getDate()
    {
        return $this->date->format('Y-m-d H:i');
    }

    public function getClientUsed()
    {
        return $this->clientUsed;
    }

    public static function getNextId($file)
    {
        $finder = new JsonFinder($file);

        return $finder->findNextStatusId();
    }
}
