<?php

header('Content-Type: text/html; charset=utf-8');

echo $_SERVER['HTTP_HOST'] . ' ';
echo date('Y-m-d H:i:s') . ' ';

$mysqli = new mysqli('localhost', 'todolist', 'todolist', 'todolist', '3306');

if (mysqli_connect_errno()) {
    printf('Connect failed: %s\n', mysqli_connect_error());
} else {
    echo 'Соединение с БД установлено! ';
}

echo 'working!';

echo print_r(exec('ls -al'), 1);