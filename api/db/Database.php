<?php

class Database
{
    private string $connection_string;
    private string $user;
    private string $password;
    private ?PDO $connection = null;

    function __construct()
    {
        $config = require 'common/config.php';

        $DB_HOST = $config['host'];
        $DB_NAME = $config['database'];
        $this->user = $config['user'];
        $this->password = $config['password'];

        $this->connection_string = "pgsql:host=$DB_HOST;dbname=$DB_NAME";
    }

    public function connect(): false|PDO|null
    {
        try {
            $this->connection = new PDO($this->connection_string, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $error) {
            error_log('Ошибка подключения к базе данных: ' . $error->getMessage());
            return false;
        }

        return $this->connection;
    }
}
