<?php



class Database
{
    private $connection_string;
    private $user;
    private $password;
    private $connection;

    function __construct()
    {
        $config = require 'config.php';

        $DB_HOST = $config['host'];
        $DB_NAME = $config['database'];
        $this->user = $config['user'];
        $this->password = $config['password'];

        $connect_string = "pgsql:host={$DB_HOST};dbname={$DB_NAME}";
    }

    public function connect()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO($this->connection_string, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $error) {
            die("Ошибка подключения к базе данных: " . $error->getMessage());
        }

        return $this->connection;
    }
}
