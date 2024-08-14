<?php

ob_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

$start_time = microtime(true);

require_once 'db/Database.php';
require_once 'controller/ContactsController.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    $db = new Database();
    $connection = $db->connect();

    if (!$connection) {
        throw new Exception('Ошибка подключения к базе данных');
    }

    $contactsController = new ContactsController($connection);

    switch ($method) {
        case 'GET':
            $contactsController->read();
            break;
        case 'POST':
            $contactsController->create();
            break;
        case 'PUT':
            $contactsController->update();
            break;
        case 'DELETE':
            $contactsController->delete();
            break;
        default:
            break;
    }
} catch (Exception $error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $error->getMessage()]);
}

$end_time = microtime(true);

$execution_time = round(($end_time - $start_time) * 1000);
$memory_usage = round(memory_get_peak_usage(true) / 1024);

header("X-Debug-Time: {$execution_time}ms");
header("X-Debug-Memory: {$memory_usage}Kb");

ob_end_flush();
