<?php

namespace Model;

class StatusDataMapper implements DataMapperInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function persist(Status $status)
    {
        // Status length max : 140 characters
        if (mb_strlen($status->getMessage()) > 140) {
            return -1;
        }
        // Content empty
        if ('' === $status->getMessage()) {
            return -2;
        }
        $query = "INSERT INTO statuses (username, content, date, clientused) VALUES (:username, :message, :date, :clientused)";
        $parameters = [
            ':username'     => $status->getUsername(),
            ':message'      => $status->getMessage(),
            ':date'         => $status->getDate(),
            ':clientused'   => $status->getClientUsed(),
        ];
        $this->connection->executeQuery($query, $parameters);

        return true;
    }

    public function remove(Status $status)
    {
        $query = "DELETE FROM statuses WHERE id = :id";
        $parameters = [':id' => $status->getId()];
        $this->connection->executeQuery($query, $parameters);

        return true;
    }
}
