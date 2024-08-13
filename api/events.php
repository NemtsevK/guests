<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

require_once 'db/Database.php';

$method = $_SERVER['REQUEST_METHOD'];

$db = new Database();
$connection = $db->connect();
$eventsController = new EventsController($connection);

switch ($method) {
    case 'GET':
        $eventsController->read();
        break;
    default:
        break;
}
