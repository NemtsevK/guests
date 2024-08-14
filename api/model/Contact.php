<?php
require_once 'common/utils.php';
require_once 'common/const.php';

class Contact
{
    private PDO $connection;
    private string $table = 'contacts';

    public int|null $id = null;
    public string|null $first_name;
    public string|null $last_name;
    public string|null $phone;
    public string|null $email;
    public string|null $country;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function insert(): array
    {
        global $TEXT, $PHONE, $EMAIL;

        $this->first_name = clean($this->first_name);
        $this->last_name = clean($this->last_name);
        $this->phone = clean($this->phone, 20);
        $this->email = clean($this->email);
        $this->country = clean($this->country);
        $this->country = is_null($this->country) ? getCountryByPhone($this->connection, $this->phone) : $this->country;

        if (is_null($this->first_name) || is_null($this->last_name) || is_null($this->phone)) {
            return ['success' => false, 'message' => 'Имя, фамилия и телефон обязательны для заполнения'];
        }

        $validationResult = validateFields([
            'first_name' => [
                'value' => $this->first_name,
                'pattern' => $TEXT,
            ],
            'last_name' => [
                'value' => $this->last_name,
                'pattern' => $TEXT,
            ],
            'phone' => [
                'value' => $this->phone,
                'pattern' => $PHONE,
            ],
            'email' => [
                'value' => $this->email,
                'pattern' => $EMAIL,
            ],
            'country' => [
                'value' => $this->country,
                'pattern' => $TEXT,
            ],
        ]);

        if (!$validationResult['success']) {
            return $validationResult;
        }

        if (checkRepeat($this->connection, $this->phone, 'phone')) {
            return ['success' => false, 'message' => 'Номер телефона должен быть уникальным'];
        }

        if (checkRepeat($this->connection, $this->email, 'email')) {
            return ['success' => false, 'message' => 'Электронная почта должна быть уникальной'];
        }

        $query = "INSERT INTO $this->table (first_name, last_name, phone, email, country)
            VALUES (?, ?, ?, ?, ?)";

        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([
                $this->first_name,
                $this->last_name,
                $this->phone,
                $this->email,
                $this->country,
            ]);
            return ['success' => true, 'message' => 'Информация о госте успешно добавлена'];
        } catch (PDOException $error) {
            error_log('Ошибка добавления гостя: ' . $error->getMessage());
            return ['success' => false, 'message' => 'Ошибка добавления гостя'];
        }
    }

    public function select(): false|PDOStatement
    {
        if (is_null($this->id)) {
            $query = "SELECT * FROM $this->table ORDER BY id";
            $params = [];
        } else {
            $query = "SELECT * FROM $this->table WHERE id = ?";
            $params = [$this->id];
        }

        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $error) {
            error_log('Ошибка получения информации: ' . $error->getMessage());
            return false;
        }
    }

    public function update(): array
    {
        global $TEXT, $PHONE, $EMAIL;

        $this->id = clean($this->id);
        $this->first_name = clean($this->first_name);
        $this->last_name = clean($this->last_name);
        $this->phone = clean($this->phone, 20);
        $this->email = clean($this->email);
        $this->country = clean($this->country);
        $this->country = is_null($this->country) ? getCountryByPhone($this->connection, $this->phone) : $this->country;

        if (is_null($this->first_name) || is_null($this->last_name) || is_null($this->phone)) {
            return ['success' => false, 'message' => 'Имя, фамилия и телефон обязательны для заполнения'];
        }

        $validationResult = validateFields([
            'first_name' => [
                'value' => $this->first_name,
                'pattern' => $TEXT,
            ],
            'last_name' => [
                'value' => $this->last_name,
                'pattern' => $TEXT,
            ],
            'phone' => [
                'value' => $this->phone,
                'pattern' => $PHONE,
            ],
            'email' => [
                'value' => $this->email,
                'pattern' => $EMAIL,
            ],
            'country' => [
                'value' => $this->country,
                'pattern' => $TEXT,
            ],
        ]);

        if (!$validationResult['success']) {
            return $validationResult;
        }

        if (checkRepeat($this->connection, $this->phone, 'phone', $this->id)) {
            return ['success' => false, 'message' => 'Номер телефона должен быть уникальным'];
        }

        if (checkRepeat($this->connection, $this->email, 'email', $this->id)) {
            return ['success' => false, 'message' => 'Электронная почта должна быть уникальной'];
        }

        $query = "UPDATE $this->table
            SET first_name = ?,
            last_name = ?,
            phone = ?,
            email = ?,
            country = ?
            WHERE id = ?";

        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([
                $this->first_name,
                $this->last_name,
                $this->phone,
                $this->email,
                $this->country,
                $this->id,
            ]);
            return ['success' => true, 'message' => 'Информация о госте успешно изменена'];
        } catch (PDOException $error) {
            error_log('Ошибка изменения гостя: ' . $error->getMessage());
            return false;
        }
    }

    public function delete(): false|PDOStatement
    {
        $this->id = clean($this->id);
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";

        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([$this->id]);
            return $statement;
        } catch (PDOException $error) {
            error_log('Ошибка удаления гостя: ' . $error->getMessage());
            return false;
        }
    }
}
