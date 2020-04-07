<?php
if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['task']))
{
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $task = trim(htmlspecialchars($_POST['task']));

    require_once 'connect.php';

    $query = ("INSERT INTO task (`name`, `email`, `task`) values (?, ?, ?)");
    $prepare = $pdo->prepare($query);
    $flag = $prepare->execute([$name, $email, $task]);

    if($flag)
        echo json_encode(['message'=> ['ok' => 'Задача добавлена']]);
    else
        echo json_encode(['message'=> ['error' => 'Произошел сбой, повторите попытку']]);
}
