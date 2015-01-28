<?php

namespace Model;

class StatusTest extends \TestCase
{
    private $status;
    private $date;

    public function setUp()
    {
        $this->date = new \DateTime();
        $this->status = new Status("message", 0, "username", $this->date);
    }

    public function testGetMessage()
    {
        $this->assertEquals("message", $this->status->getMessage());
    }

    public function testGetId()
    {
        $this->assertEquals(0, $this->status->getId());
    }

    public function testGetUsername()
    {
        $this->assertEquals("username", $this->status->getUsername());
    }

    public function testGetDate()
    {
        $this->assertEquals($this->date->format('Y-M-d H:i'), $this->status->getDate());
    }

    public function testGetClientUsed()
    {
        $this->assertEquals("PC", $this->status->getClientUsed());
    }
}
