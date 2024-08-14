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

        $statement = $items->select();

        if ($statement === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Ошибка получения списка стран']);
            return;
        }

        $items = $statement->fetchAll();

        echo json_encode(['success' => true, 'message' => 'Успешно получен список стран', 'items' => $items]);
    }
}
