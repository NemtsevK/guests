<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

require_once 'db/Database.php';
require_once 'controller/CountriesController.php';

$method = $_SERVER['REQUEST_METHOD'];

$db = new Database();
$connection = $db->connect();
$countriesController = new CountriesController($connection);

switch ($method) {
    case 'GET':
        $countriesController->read();
        break;
    default:
        break;
}
