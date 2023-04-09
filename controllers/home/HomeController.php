<?php
namespace controllers\home;

use models\todo\tasks\TaskModel;

class HomeController {
    public function index() {
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        $taskmodel = new TaskModel();
        $tasks = $taskmodel->getAllTasksByIdUser($user_id);
        $tasksJson = json_encode($tasks);
        
        include 'app/views/index.php';
    }
}
