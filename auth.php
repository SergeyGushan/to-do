<?php
session_start();
if(!$_SESSION['auth']) {
    if (empty($_POST)) {
        $_SESSION['auth'] = false;
    } else {

        $login = 'admin';
        $password = '123';

        if ($_POST['login'] === $login && $_POST['password'] === $password) {
            $_SESSION['auth'] = true;
            echo json_encode(['auth' => 'true', 'message' => ['ok' => 'Вы авторизованны']]);
        } else {
            file_put_contents('log.txt', $_POST['login']);
            $_SESSION['auth'] = false;
            echo json_encode(['auth' => 'false', 'message' => ['error' => 'Неверный логин или пароль']]);
        }
    }
} else {
    $_SESSION['auth'] = false;
    echo json_encode(['auth' => 'false', 'message' => ['error' => 'Авторизуйтесь']]);
}



