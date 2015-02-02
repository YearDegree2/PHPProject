<?php

namespace Model;

class DatabaseFinder implements FinderInterface
{
    private $databaseConnection;

    public function __construct($databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    public function findAll()
    {
        $preparedQuery = $this->databaseConnection->prepare("SELECT * FROM statuses");
        $preparedQuery->execute();

        $arrayStatuses = array();
        foreach ($preparedQuery->fetchALL(\PDO::FETCH_ASSOC) as $result) {
            array_push($arrayStatuses, new Status($result['content'], $result['id'], $result['username'], new \DateTime($result['date']), $result['clientused']));
        }

        return $arrayStatuses;
    }

    public function findOneById($id)
    {
        $preparedQuery = $this->databaseConnection->prepare("SELECT * FROM statuses WHERE id = :id");
        $values = [':id' => $id];
        $preparedQuery->execute($values);

        $result = $preparedQuery->fetch(\PDO::FETCH_ASSOC);

        return ($result !== false) ? new Status($result['content'], $result['id'], $result['username'], new \DateTime($result['date']), $result['clientused']) : null;
    }

    public function addStatus(Status $status)
    {
        $preparedQuery = $this->databaseConnection->prepare("INSERT INTO statuses (username, content, date, clientused) VALUES (:username, :message, :date, :clientused)");
        $values = [
            ':username'     => $status->getUsername(),
            ':message'      => $status->getMessage(),
            ':date'         => $status->getDate(),
            ':clientused'   => $status->getClientUsed(),
        ];
        $preparedQuery->execute($values);
    }

    public function deleteStatus(Status $status)
    {
        $preparedQuery = $this->databaseConnection->prepare("DELETE FROM statuses WHERE id = :id");
        $values = [':id' => $status->getId()];
        $preparedQuery->execute($values);
    }
}
