<?php

class Country
{
    private PDO $connection;
    private string $table = 'countries';

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function select(): false|PDOStatement
    {
        $query = "SELECT * FROM $this->table ORDER BY name";

        try {
            $statement = $this->connection->prepare($query);
            $statement->execute();
            return $statement;
        } catch (PDOException $error) {
            error_log('Ошибка получения списка стран: ' . $error->getMessage());
            return false;
        }
    }
}
