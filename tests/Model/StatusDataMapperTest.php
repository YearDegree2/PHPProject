<?php

namespace Model;

class StatusDataMapperTest extends \TestCase
{
    private $connection;
    private $dataMapper;

    public function setUp()
    {
        $this->connection = $this->getMock('Model\MockConnection');
        $this->dataMapper = new StatusDataMapper($this->connection);
    }

    public function testPersistContentTooLong()
    {
        $status = new Status("content too long!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!",
            0, "Nico", new \DateTime());
        $this->assertEquals(-1, $this->dataMapper->persist($status));
    }

    public function testPersistEmptyContent()
    {
        $status = new Status("", 0, "Nico", new \DateTime());
        $this->assertEquals(-2, $this->dataMapper->persist($status));
    }

    public function testPersistOk()
    {
        $status = new Status("message", 0, "Nico", new \DateTime());
        $this->assertTrue($this->dataMapper->persist($status));
    }

    public function testRemove()
    {
        $status = new Status("message", 0, "Nico", new \DateTime());
        $this->assertTrue($this->dataMapper->remove($status));
    }
}
