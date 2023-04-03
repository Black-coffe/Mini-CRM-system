<?php

namespace controllers\todo\tasks;

use models\todo\tasks\TaskModel;
use models\todo\category\CategoryModel;
use models\Check;

class TaskController{

    private $check;

    public function __construct()
    {
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new Check($userRole);
    }

    public function index(){
        $this->check->requirePermission();

        $taskModel = new TaskModel();
        $tasks = $taskModel->getAllTasks();

        include 'app/views/todo/tasks/index.php';
    }

    public function create(){
        $this->check->requirePermission();

        $todoCategoryModel = new CategoryModel();
        $categories = $todoCategoryModel->getAllCategoriesWithUsability();

        include 'app/views/todo/tasks/create.php';
    }

    public function store(){

        $this->check->requirePermission();

        if(isset($_POST['title']) && isset($_POST['category_id']) && isset($_POST['finish_date'])){
            $data['title'] = trim($_POST['title']);
            $data['category_id'] = trim($_POST['category_id']);
            $data['finish_date'] = trim($_POST['finish_date']);
            $data['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            $data['status'] = 'new';
            $data['priority'] = 'low';

            $taskModel = new TaskModel();
            $taskModel->createTask($data);

        }
        $path = '/'. APP_BASE_PATH . '/todo/tasks';
        header("Location: $path");
    }

    public function edit($params){
        $this->check->requirePermission();

        $todoCategoryModel = new CategoryModel();
        $category = $todoCategoryModel->getCategoryById($params['id']);

        if(!$category){
            echo "Category not found";
            return;
        }

        include 'app/views/todo/category/edit.php';
    }


    public function update($params){
        $this->check->requirePermission();

        if(isset($params['id']) && isset($_POST['title']) && isset($_POST['description'])){
            $id = trim($params['id']);
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $usability  = isset($_POST['usability']) ? $_POST['usability'] : 0;

            if (empty($title) || empty($description)) {
                echo "Title and Description are required";
                return;
            }

            $todoCategoryModel = new CategoryModel();
            $todoCategoryModel->updateCategory($id, $title, $description, $usability);
        }
        $path = '/'. APP_BASE_PATH . '/todo/category';
        header("Location: $path");
    }

    public function delete($params){
        $this->check->requirePermission();

         $todoCategoryModel = new CategoryModel();
        $todoCategoryModel->deleteCategory($params['id']);

        $path = '/'. APP_BASE_PATH . '/todo/category';
        header("Location: $path");
    }
}