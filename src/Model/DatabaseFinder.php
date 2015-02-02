<?php

namespace Model;

class DatabaseFinder implements FinderInterface
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function findAll()
    {
        $query = "SELECT * FROM statuses";
        $results = $this->connection->executeQuery($query);
        $results = $results->fetchALL(\PDO::FETCH_ASSOC);

        $arrayStatuses = array();
        foreach ($results as $result) {
            array_push($arrayStatuses, new Status($result['content'], $result['id'], $result['username'], new \DateTime($result['date']), $result['clientused']));
        }

        return $arrayStatuses;
    }

    public function findOneById($id)
    {
        $query = "SELECT * FROM statuses WHERE id = :id";
        $parameters = [':id' => $id];

        $result = $this->connection->executeQuery($query, $parameters);
        $result = $result->fetch(\PDO::FETCH_ASSOC);

        return ($result !== false) ? new Status($result['content'], $result['id'], $result['username'], new \DateTime($result['date']), $result['clientused']) : null;
    }

    public function addStatus(Status $status)
    {
        $query = "INSERT INTO statuses (username, content, date, clientused) VALUES (:username, :message, :date, :clientused)";
        $parameters = [
            ':username'     => $status->getUsername(),
            ':message'      => $status->getMessage(),
            ':date'         => $status->getDate(),
            ':clientused'   => $status->getClientUsed(),
        ];
        $this->connection->executeQuery($query, $parameters);
    }

    public function deleteStatus(Status $status)
    {
        $query = "DELETE FROM statuses WHERE id = :id";
        $parameters = [':id' => $status->getId()];
        $this->connection->executeQuery($query, $parameters);
    }
}
