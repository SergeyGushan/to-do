<?php
if($_SESSION['auth'] == true) {
    if (!empty($_POST['select']) && !empty($_POST['id']))
        file_put_contents('log.txt', $_POST['id']);
    {
        require_once 'connect.php';
        $select = $_POST['select'];
        $id = $_POST['id'];

        if ($select == 'completed' || $select == 'performed') {
            if ($select === 'completed') {
                $select = 0;
            }

            if ($select === 'performed') {
                $select = 1;
            }

            $query = "UPDATE task SET status= $select WHERE `id` = $id ";
            $prepare = $pdo->prepare($query);
            $flag = $prepare->execute();
            if ($flag) {
                echo "Измененно!";
            } else {
                echo "Ошибка, повторите попытку!";
            }
        }
    }
} else {
    $_SESSION['auth'] = false;
    echo "Ошибка авторизации, перезагрузите страницу";
}