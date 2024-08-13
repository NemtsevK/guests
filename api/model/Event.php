<?php
class Event
{
    private $connection;
    private $table = 'contacts';

    public $id;
    public $time;
    public $details;
    public $team_home_fk;
    public $team_away_fk;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function list()
    {
        $query = 'SELECT id ,
            first_name,
            last_name,
            phone,
            email,
            country
            FROM ' . $this->table . '
            ORDER BY id';

        $statement = $this->connection->prepare($query);
        $statement->execute();

        return $statement;
    }
}
