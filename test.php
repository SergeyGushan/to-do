<?php
require_once 'connect.php';

$query = "UPDATE task SET status= 0 WHERE id = 28 ";
$prepare = $pdo->prepare($query);
$flag = $prepare->execute();

var_dump($prepare->errorInfo());
