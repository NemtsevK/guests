<?php

$config = require 'config.php';

try {
    $connect_string = "pgsql:host={$config['host']};dbname={$config['database']}";

    $connect = new PDO($connect_string, $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $error) {
    die("Ошибка подключения к базе данных: " . $error->getMessage());
}
