<?php
require_once 'connect.php';
require_once 'utils.php';
require_once 'const.php';

global $connect, $TEXT, $PHONE, $EMAIL;

$start_time = microtime(true);
$start_memory = memory_get_usage();

$first_name = clean($_POST['first_name']);
$last_name = clean($_POST['last_name']);
$phone = clean($_POST['phone'], 20);
$email = clean($_POST['email']);
$country = clean($_POST['country']);

if (!validateRequiredFields([$first_name, $last_name, $phone])) {
    sendJsonResponse(false, 'Имя, фамилия и телефон обязателен для заполнения');
}

$validationResult = validateFields([
    'first_name' => [
        'value' => $first_name,
        'pattern' => $TEXT,
    ],
    'last_name' => [
        'value' => $last_name,
        'pattern' => $TEXT,
    ],
    'phone' => [
        'value' => $phone,
        'pattern' => $PHONE,
    ],
    'email' => [
        'value' => $email,
        'pattern' => $EMAIL,
    ],
    'country' => [
        'value' => $country,
        'pattern' => $TEXT,
    ],
]);

if (!$validationResult['success']) {
    header('Content-Type: application/json');
    echo json_encode($validationResult);
    exit;
}

if (checkRepeat($phone, 'phone')) {
    sendJsonResponse(false, 'Номер телефона должен быть уникальным');
}

if (checkRepeat($email, 'email')) {
    sendJsonResponse(false, 'Электронная почта должна быть уникальной');
}

$country = is_null($country) ? getCountryByPhone($phone) :  $country;

$query = $connect->prepare("
    INSERT INTO contacts (first_name, last_name, phone, email, country)
    VALUES (?, ?, ?, ?, ?)
");

$success = $query->execute([$first_name, $last_name, $phone, $email, $country]);

$response_time = microtime(true) - $start_time;
$response_memory = (memory_get_usage() - $start_memory) / 1024;

header('X-Debug-Time: ' . $response_time * 1000 . 'ms');
header('X-Debug-Memory: ' . $response_memory . 'KB');
header('Content-Type: application/json');
echo json_encode(['success' => $success, 'message' => 'Информация о госте успешно добавлена']);
