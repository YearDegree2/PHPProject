<?php

namespace Model;

class UserTest extends \TestCase
{
    private $user;

    public function setUp()
    {
        $this->user = new User(0, 'Nico', 'aze');
    }

    public function testGetId()
    {
        $this->assertEquals(0, $this->user->getId());
    }

    public function testGetUsername()
    {
        $this->assertEquals('Nico', $this->user->getUsername());
    }

    public function testGetPassword()
    {
        $this->assertEquals('aze', $this->user->getPassword());
    }
}
