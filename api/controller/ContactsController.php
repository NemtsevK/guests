<?php
require_once 'db/Database.php';
require_once 'model/Contact.php';

class ContactsController
{
    private ?PDO $connection;

    function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function create(): void
    {
        $item = new Contact($this->connection);
        $data = json_decode(file_get_contents("php://input"));

        $item->first_name = $data->first_name;
        $item->last_name = $data->last_name;
        $item->phone = $data->phone;
        $item->email = $data->email;
        $item->country = $data->country;

        $statement = $item->insert();

        if ($statement) {
            $success = true;
            $message = 'Информация о госте успешно добавлена';
        } else {
            http_response_code(500);
            $success = false;
            $message = 'Ошибка добавления гостя';
        }

        echo json_encode(['success' => $success, 'message' => $message]);
    }

    public function read(): void
    {
        $items = new Contact($this->connection);
        $is_update = isset($_GET['id']);
        $items->id = $_GET['id'] ?? null;
        $statement = $items->select();

        if (!$statement) {
            http_response_code(500);
            $message = $is_update ? 'Ошибка получения информации о госте' : 'Ошибка получения списка гостей';
            echo json_encode(['success' => false,'message' => $message]);
            return;
        }

        $items = $is_update ? $statement->fetch() : $statement->fetchAll();

        echo json_encode(['success' => true, 'message' => 'Успешно', 'items' => $items]);
    }

    public function update(): void
    {
        $item = new Contact($this->connection);
        $data = json_decode(file_get_contents("php://input"));

        $item->id = $data->id;
        $item->first_name = $data->first_name;
        $item->last_name = $data->last_name;
        $item->phone = $data->phone;
        $item->email = $data->email;
        $item->country = $data->country;

        $statement = $item->update();

        if ($statement) {
            $success = true;
            $message = 'Информация о госте успешно изменена';
        } else {
            http_response_code(500);
            $success = false;
            $message = 'Ошибка изменения гостя';
        }

        echo json_encode(['success' => $success, 'message' => $message]);
    }

    public function delete(): void
    {
        $item = new Contact($this->connection);
        $data = json_decode(file_get_contents("php://input"));
        $item->id = $data->id;
        $statement = $item->delete();

        if($statement) {
            $success = true;
            $message = 'Информация о госте успешно удалена';
        } else {
            http_response_code(500);
            $success = false;
            $message = 'Ошибка удаления гостя';
        }

        echo json_encode(['success' => $success, 'message' => $message]);
    }
}
