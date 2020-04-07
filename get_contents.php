<?php

if(!empty($_POST['select']))
{
    $select = $_POST['select'];
    if($select === 'id' || $select === 'name' || $select === 'email' || $select === 'status')
    {
        require_once 'connect.php';
        $query = "SELECT * FROM task ORDER BY `$select` DESC ";
        $prepare = $pdo->prepare($query);
        $prepare->execute();
        if($prepare)
        {
            $fetch = $prepare->fetchAll();
            file_put_contents('log.txt', json_encode($fetch));
            echo json_encode($fetch);
        }

    }



}