<?php
require_once 'connect.php';
require_once 'utils.php';

global $connect;

$start_time = microtime(true);
$start_memory = memory_get_usage();

$id = clean($_POST['id']);
$first_name = clean($_POST['first_name']);
$last_name = clean($_POST['last_name']);
$phone = clean($_POST['phone'], 20);
$email = clean($_POST['email']);
$country = clean($_POST['country']);

if (!validateRequiredFields([$first_name, $last_name, $phone])) {
    sendJsonResponse(false, 'Имя, фамилия и телефон обязателен для заполнения');
}

if (checkRepeat($phone, 'phone', $id)) {
    sendJsonResponse(false, 'Номер телефона должен быть уникальным');
}

if (checkRepeat($email, 'email', $id)) {
    sendJsonResponse(false, 'Электронная почта должна быть уникальной');
}

$country = is_null($country) ? getCountryByPhone($phone) :  $country;

$query = $connect->prepare("
    UPDATE contacts
    SET first_name = ?,
    last_name = ?,
    phone = ?,
    email = ?,
    country = ?
    WHERE id = ?
");

$success = $query->execute([$first_name, $last_name, $phone, $email, $country, $id]);

$response_time = microtime(true) - $start_time;
$response_memory = (memory_get_usage() - $start_memory) / 1024;

header('X-Debug-Time: ' . $response_time * 1000 . 'ms');
header('X-Debug-Memory: ' . $response_memory . 'KB');
header('Content-Type: application/json');
echo json_encode(['success' => $success, 'message' => 'Информация о госте успешно изменена']);
