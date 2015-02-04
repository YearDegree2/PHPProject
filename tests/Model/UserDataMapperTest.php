<?php

namespace Model;

class UserDataMapperTest extends \TestCase
{
    private $connection;
    private $dataMapper;

    public function setUp()
    {
        $this->connection = $this->getMock('Model\MockConnection');
        $this->dataMapper = new UserDataMapper($this->connection);
    }

    public function testPersistUsernameToolong()
    {
        $user = new User(0, "azertyazertyazertyazertyazertyazer", "pass");
        $this->assertEquals(-1, $this->dataMapper->persist($user));
    }

    public function testPersistPasswordTooLong()
    {
        $user = new User(0, "username", "azertyazertyazertyazertyazertyazer");
        $this->assertEquals(-1, $this->dataMapper->persist($user));
    }

    public function testPersistOk()
    {
        $user = new User(0, "username", "password");
        $this->assertTrue($this->dataMapper->persist($user));
    }
}
