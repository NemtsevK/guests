<?php
require_once 'db/Database.php';
require_once 'model/Country.php';

class CountriesController
{
    private ?PDO $connection;

    function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function read(): void
    {
        $items = new Country($this->connection);

        $statement = $items->list();
        $count = $statement->rowCount();

        if ($count > 0) {
            $message = 'Успешно';
            $items =  $statement->fetchAll();
        } else {
            $message = 'Нет записей';
            $items = null;
        }

        echo json_encode(['message' => $message, 'items' => $items]);
    }
}
