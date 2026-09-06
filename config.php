<?php
$host = 'localhost';
$dbname = 'w92350sl_1';
$user = 'логин';
$password = 'пароль';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Ошибка подключения к базе: ' . $e->getMessage());
}