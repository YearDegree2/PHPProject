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

    public function executeQuery($query, array $parameters = array())
    {
        $preparedQuery = $this->prepare($query);
        foreach ($parameters as $key => $value) {
            $this->bind($preparedQuery, $key, $value);
        }
        $preparedQuery->execute();

        return $preparedQuery;
    }

    private function bind($query, $key, $value)
    {
        switch ($key) {
            case ':limit':
                $query->bindValue($key, $value, self::PARAM_INT);
                break;
            default:
                $query->bindValue($key, $value, self::PARAM_STR);
                break;
        }
    }
}
