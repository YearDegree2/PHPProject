<?php

namespace Model;

class JsonFinderTest extends \TestCase
{
    private $finder;

    public function setUp()
    {
        $this->finder = new JsonFinder(__DIR__ . DIRECTORY_SEPARATOR . '../../data/statuses.json');
    }

    public function testFindAll()
    {
        $values = $this->finder->findAll();
        $date = new \DateTime("2015-Jan-21 18:17");
        $status = new Status("Vive les USA", 0, "Nico", $date, "Windows Phone");

        $this->assertNotEmpty($values);
        $this->assertArrayHasKey(2, $values);
        $this->assertTrue(in_array($status, $values));
    }

    public function testFindOneByIdExisting()
    {
        $value = $this->finder->findOneById(0);
        $date = new \DateTime("2015-Jan-21 18:17");
        $status = new Status("Vive les USA", 0, "Nico", $date, "Windows Phone");

        $this->assertNotEmpty($value);
        $this->assertEquals($status, $value);
    }

    public function testFindOneByIdNotExisting()
    {
        $value = $this->finder->findOneById(1000);

        $this->assertEmpty($value);
        $this->assertNull($value);
    }

    public function testAddStatus()
    {
        $date = new \DateTime("2015-Jan-21 18:17");
        $status = new Status("Vive les USA", 3000, "Nico", $date, "Windows Phone");
        $this->finder->addStatus($status);

        $this->assertEquals($status, $this->finder->findOneById(3000));
    }

    /**
     * @expectedException        Exception\HttpException
     */
    public function testAddStatusIdAlreadyExisting()
    {
        $status = new Status("Status deja existant", 3000, "Existant", new \DateTime());
        $this->finder->addStatus($status);
    }

    public function testDeleteStatus()
    {
        $date = new \DateTime("2015-Jan-21 18:17");
        $status = new Status("Vive les USA", 3000, "Nico", $date, "Windows Phone");
        $this->finder->deleteStatus($status);

        $this->assertNull($this->finder->findOneById(3000));
    }
}
