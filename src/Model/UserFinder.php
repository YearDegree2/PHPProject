<?php

namespace Model;

class UserFinder implements FinderInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll()
    {
        // Not implemented
    }

    public function findOneById($id)
    {
        // Not implemented
    }

    public function findOneByUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $parameters = [':username' => $username];
        $result = $this->connection->executeQuery($query, $parameters);
        $result = $result->fetch(\PDO::FETCH_ASSOC);

        return ($result !== false) ? new User($result['id'], $result['username'], $result['password']) : null;
    }

    public function verifyPassword($username, $password)
    {
        $user = $this->findOneByUsername($username);
        if (null !== $user) {
            return password_verify($password, $user->getPassword());
        }

        return false;
    }
}
