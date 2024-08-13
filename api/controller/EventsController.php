<?php
include_once '../db/Database.php';
include_once '../model/Event.php';

class EventsController
{
    private $connection;

    function __construct($connection)
    {
        $this->connection = $connection;
    }


    public function read(): void
    {
        $items = new Event($this->connection);

        $statement = $items->list();
        $count = $statement->rowCount();

        if ($count > 0) {
            $message = '';
            $items =  $statement->fetchAll();
        } else {
            $message = 'Нет записей';
            $items = null;
        }
        echo json_encode(['message' => $message, 'items' => $items]);
    }
}
