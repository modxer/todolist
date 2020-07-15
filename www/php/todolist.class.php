<?php

class toDoList {
    public $db;

    public function __construct()
    {
        $config = include 'config.php';

        $this->db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

        if (mysqli_connect_errno()) {
            printf("Нет соединения с БД: %s\n", mysqli_connect_error());
            exit();
        } else {
            //echo "Соединение установлено";
        }

        $this->db->set_charset('utf8');

        // создаём таблицы

        $sql = 'CREATE TABLE `tasks` (
          `id` int(10)  UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `title` varchar(255) NOT NULL DEFAULT \'\'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        $this->db->query($sql);

        $sql = 'CREATE TABLE `log` (
          `id` int(10)  UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `task_id` int(10) UNSIGNED NOT NULL,
          `ip` varchar(100) NOT NULL DEFAULT "",
          `date` varchar(20) NOT NULL DEFAULT ""
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        $this->db->query($sql);
    }

    /**
     * @param string $title
     * @return bool
     */
    public function addTask($title = '') {
        $sql = "INSERT INTO `tasks` (`title`) VALUES ('{$title}');";
        $success = $this->db->query($sql);

        if($success) {
            $sql = "SELECT * FROM tasks WHERE id=LAST_INSERT_ID();";
            $last = $this->db->query($sql);
            $task = [];
            if($last) {
                $task = $last->fetch_assoc();

                $ip = $_SERVER['REMOTE_ADDR'];
                $date = date('Y-m-j H:i:s');

                $sql = "INSERT INTO `log` (`task_id`, `ip`, `date`) VALUES ({$task['id']}, '{$ip}', '{$date}');";
                $this->db->query($sql);
            }
        }

        $this->response([
            'success' => $success,
            'task' => $task,
            'html' => $this->getTaskHtml($task)
        ]);

        return true;
    }

    public function removeTask($id) {
        echo 'removeTask';
    }

    public function getList() {
        $sql = "SELECT id, title FROM tasks ORDER BY id DESC;";
        $result = $this->db->query($sql);

        $html = '';
        while ($row = $result->fetch_assoc()) {
            $html .= $this->getTaskHtml($row);
        }

        $this->response([
            'success' => true,
            'html' => $html
        ]);
    }

    public function response($response) {
        echo json_encode($response);
        exit();
    }

    public function getTaskHtml($data) {
        $tpl = file_get_contents('../tpls/task_row.tpl');
        foreach ($data as $key=>$value) {
            $tpl = str_replace('{{' . $key .'}}', $value, $tpl);
        }
        return $tpl;
    }

    public function __destruct()
    {
        $this->db->close();
    }
}