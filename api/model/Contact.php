<?php
require_once 'common/utils.php';

class Contact
{
    private PDO $connection;
    private string $table = 'contacts';

    public int|null $id = null;
    public string $first_name;
    public string $last_name;
    public string $phone;
    public string|null $email = null;
    public string|null $country = null;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function insert(): false|PDOStatement
    {
        $this->first_name = clean($this->first_name);
        $this->last_name = clean($this->last_name);
        $this->phone = clean($this->phone, 20);
        $this->email = clean($this->email);
        $this->country = clean($this->country);
        $this->country = is_null($this->country) ? getCountryByPhone($this->connection, $this->phone) :  $this->country;

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
            return $statement;
        } catch (PDOException $error) {
            error_log('Ошибка добавления гостя: ' . $error->getMessage());
            return false;
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

    public function update(): false|PDOStatement
    {
        $this->id = clean($this->id);
        $this->first_name = clean($this->first_name);
        $this->last_name = clean($this->last_name);
        $this->phone = clean($this->phone, 20);
        $this->email = clean($this->email);
        $this->country = clean($this->country);
//        $this->country = is_null($this->country) ? getCountryByPhone($this->connection, $this->phone) :  $this->country;
        $this->country = is_null($this->country) ? getCountryByPhone($this->connection, $this->phone) :  $this->country;

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
            return $statement;
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
