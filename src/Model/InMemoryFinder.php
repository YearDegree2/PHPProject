<?php

namespace Model;

class InMemoryFinder implements FinderInterface
{
    private $statuses = array();

    public function __construct()
    {
        array_push($this->statuses, new Status("Vive les USA", 1, "Nico", new \DateTime(), "Windows Phone"));
        array_push($this->statuses, new Status("Toto est en route pour la presidence", 2, "Clement", new \DateTime()));
        array_push($this->statuses, new Status("Allez Boston! Meme si y sont pourris :/", 3, "Yannis", new \DateTime(), "Mac"));
    }

    public function findAll()
    {
        return $this->statuses;
    }

    public function findOneById($id)
    {
        return isset($this->statuses[$id]) ? $this->statuses[$id] : null;
    }
}
