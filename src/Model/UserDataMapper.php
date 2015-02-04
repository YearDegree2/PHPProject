<?php

namespace Model;

class UserDataMapper
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function persist(User $user)
    {
        if (mb_strlen(trim($user->getUsername())) > 30 || mb_strlen(trim($user->getPassword())) > 30) {
            return -1;
        }
        $passwordHash = $this->hashPassword(trim($user->getPassword()));
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $parameters = [
            ':username' => trim($user->getUsername()),
            ':password' => $passwordHash,
        ];
        $this->connection->executeQuery($query, $parameters);

        return true;
    }

    private function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
