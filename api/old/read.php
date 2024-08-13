<?php
require_once 'connect.php';

global $connect;

$start_time = microtime(true);
$start_memory = memory_get_usage();

$id = isset($_POST['id']) ? intval($_POST['id']) : null;

if ($id) {
    $query = $connect->prepare("SELECT * FROM contacts WHERE id = ?");
    $query->execute([$id]);
    $result = $query->fetch();
} else {
    $query = $connect->query("SELECT * FROM contacts ORDER BY id");
    $result = $query->fetchAll();
}

$response_time = microtime(true) - $start_time;
$response_memory = (memory_get_usage() - $start_memory) / 1024;

header('X-Debug-Time: ' . $response_time * 1000 . 'ms');
header('X-Debug-Memory: ' . $response_memory . 'KB');
header('Content-Type: application/json');
echo json_encode($result);
