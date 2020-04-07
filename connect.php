<?php
session_start();
try
{
    $pdo = new PDO('mysql:host=localhost;dbname=task', 'root', '');

} catch (PDOException $e) {
    //в случае неудачного подключение к БД, выводим сообщение об ошибке, ошибку записываем в файл
    $log = file_get_contents('log.txt');
    $log .= '[' . date('d-m-Y H:i:s') . "][{$_SERVER['SCRIPT_FILENAME']}]\nОшибка подлкючения к базе данных ($e)\n\n";
    file_put_contents('log.txt', $log);
    echo json_encode(['message'=> ['error' => 'Возникла ошибка, повторите попытку позже']]);
}