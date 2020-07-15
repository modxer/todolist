<?php

include 'todolist.class.php';

$todo = new toDoList();

$data = file_get_contents("php://input");
$data = json_decode($data);

if(!empty($data->action)) $action = $data->action;
if(!empty($data->title))$title = $data->title;
if(!empty($data->id)) $id = $data->id;
if(!empty($data->offset)) $offset = $data->offset;
if(!empty($data->limit)) $limit = $data->limit;

switch ($action) {
    case 'add':
        $todo->addTask($title);
        break;

    case 'remove':
        $todo->removeTask($id);
        break;

    case 'getlist':
        $todo->getList();
        break;
}
