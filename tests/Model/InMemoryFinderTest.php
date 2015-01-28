<?php

namespace Model;

class InMemoryFinderTest extends \TestCase
{
    private $finder;

    public function setUp()
    {
        $this->finder = new InMemoryFinder();
    }

    public function testFindAll()
    {
        $values = $this->finder->findAll();
        $status = new Status("Vive les USA", 1, "Nico", new \DateTime(), "Windows Phone");

        $this->assertNotEmpty($values);
        $this->assertArrayHasKey(2, $values);
        $this->assertTrue(in_array($status, $values));
    }

    public function testFindOneByIdExisting()
    {
        $value = $this->finder->findOneById(1);

        $this->assertNotEmpty($value);
    }

    /**
     * @expectedException        Exception\HttpException
     */
    public function testFindOneByIdNotExisting()
    {
        $this->finder->findOneById(1000);
    }
}
