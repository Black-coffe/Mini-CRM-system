<?php
namespace controllers\home;

use models\todo\tasks\TaskModel;
use models\users\User;
use models\pages\PageModel;

class HomeController {

    public function __construct(){
        $user = new User();
        $user->createTable();

        $pages = new PageModel();
        if($pages->createTable()){
            // если таблица создалась
            $pages->insertPages();
        }
    }

    public function index() {
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        $taskmodel = new TaskModel();
        $tasks = $taskmodel->getAllTasksByIdUser($user_id);
        $tasksJson = json_encode($tasks);
        
        include 'app/views/index.php';
    }
}
