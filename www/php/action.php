<?php

/**
 * Обработчик запросов
 */

include 'todolist.class.php';

$todo = new toDoList();

$data = file_get_contents("php://input");
$data = json_decode($data);

$param = [];
if(!empty($data->action)) $param['action'] = $data->action;
if(!empty($data->title)) $param['title'] = $data->title;
if(!empty($data->id)) $param['id'] = $data->id;

// простая фильтрация
foreach ($param as $key=>$value) {
    $param[$key] = strip_tags($value);
    $param[$key] = htmlentities($value);
}

switch ($param['action']) {
    case 'add':
        $todo->addTask($param['title']);
        break;

    case 'remove':
        $todo->removeTask($param['id']);
        break;

    case 'getlist':
        $todo->getList();
        break;
}
