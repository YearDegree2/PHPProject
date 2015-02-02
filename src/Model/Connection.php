<?php

namespace Model;

class Connection extends \PDO
{
    public function __construct($db_type, $db_name, $db_host, $db_user, $db_password)
    {
        try {
            parent::__construct($db_type . ':dbname=' . $db_name . ';host=' . $db_host, $db_user, $db_password);
        } catch (\PDOException $e) {
            echo 'Can\'t contact database : ' . $e->getMessage();
        }
    }

    public function executeQuery($query, $parameters = null)
    {
        $preparedQuery = $this->prepare($query);
        $preparedQuery->execute($parameters);

        return $preparedQuery;
    }
}
