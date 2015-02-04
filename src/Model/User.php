<?php

namespace Model;

class User
{
    private $id;
    private $username;
    private $password;

    public function __construct($id, $username, $password)
    {
        $this->id           = $id;
        $this->username     = $username;
        $this->password     = $password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function isValid()
    {
        if (strlen(trim($this->getUsername())) !== 0 && strlen(trim($this->getPassword())) !== 0) {
            return $this->verifyAvailability($this->getUsername());
        }

        return false;
    }

    private function verifyAvailability($username)
    {
        $finder = new UserFinder(new Connection("mysql", "uframework", "localhost", "uframework", "passw0rd"));
        $user = $finder->findOneByUsername($username);

        return (null !== $user) ? false : true;
    }
}
