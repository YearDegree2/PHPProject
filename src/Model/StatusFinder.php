<?php

namespace Model;

class StatusFinder implements FinderInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll($limit = null, $orderBy = null, $direction = null, $username = null)
    {
        $columns = array(
            'message',
            'id',
            'username',
            'date',
            'clientUsed',
        );

        $query = "SELECT * FROM statuses";
        $parameters = array();
        if (null !== $username) {
            $query .= " WHERE username = :username";
            $parameters[':username'] = $username;
        }
        if (null === $orderBy || !in_array($orderBy, $columns)) {
            $query .= " ORDER BY id ";
            $query .= ('ASC' === $direction) ? "ASC" : "DESC";
        }
        if (null !== $orderBy && in_array($orderBy, $columns)) {
            $query .= " ORDER BY " . $orderBy . " ";
            $query .= ('ASC' === $direction) ? "ASC" : "DESC";
        }
        if (null !== $limit && $limit > 0) {
            $query .= " LIMIT 0, :limit";
            $parameters[':limit'] = $limit;
        }

        $results = $this->connection->executeQuery($query, $parameters);
        $results = $results->fetchALL(\PDO::FETCH_ASSOC);

        $arrayStatuses = array();
        foreach ($results as $result) {
            array_push($arrayStatuses, new Status($result['message'], $result['id'], $result['username'], new \DateTime($result['date']), $result['clientused']));
        }

        return $arrayStatuses;
    }

    public function findOneById($id)
    {
        $query = "SELECT * FROM statuses WHERE id = :id";
        $parameters = [':id' => $id];

        $result = $this->connection->executeQuery($query, $parameters);
        $result = $result->fetch(\PDO::FETCH_ASSOC);

        return ($result !== false) ? new Status($result['message'], $result['id'], $result['username'], new \DateTime($result['date']), $result['clientused']) : null;
    }
}
