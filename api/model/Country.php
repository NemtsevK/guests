<?php

class Country
{
    private PDO $connection;
    private string $table = 'countries';

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function list(): false|PDOStatement
    {
        $query = "SELECT * FROM $this->table ORDER BY name";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        return $statement;
    }
}
