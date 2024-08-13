<?php
require_once 'connect.php';

global $connect;

$start_time = microtime(true);
$start_memory = memory_get_usage();

$id = $_POST['id'];

$query = $connect->prepare("DELETE FROM contacts WHERE id = ?");
$success = $query->execute([$id]);

$response_time = microtime(true) - $start_time;
$response_memory = (memory_get_usage() - $start_memory) / 1024;

header('X-Debug-Time: ' . $response_time * 1000 . 'ms');
header('X-Debug-Memory: ' . $response_memory . 'KB');
header('Content-Type: application/json');
echo json_encode(['success' => $success, 'message' => 'Информация о госте успешно удалена']);
