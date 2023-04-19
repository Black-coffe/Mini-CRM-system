<?php

namespace controllers\todo\category;

use models\todo\category\CategoryModel;
use models\Check;

class CategoryController{

    private $check;

    public function __construct()
    {
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new Check($userRole);
    }

    public function index(){
        $this->check->requirePermission();

        $todoCategoryModel = new CategoryModel();
        $categories = $todoCategoryModel->getAllCategories();

        include 'app/views/todo/category/index.php';
    }

    public function create(){
        $this->check->requirePermission();
        include 'app/views/todo/category/create.php';
    }

    public function store(){
        $this->check->requirePermission();
        if(isset($_POST['title']) && isset($_POST['description'])){
            $title = trim(htmlspecialchars($_POST['title']));
            $description = trim(htmlspecialchars($_POST['description']));
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

            if (empty($title) || empty($description)) {
                echo "Title and Description are required!";
                return;
            }

            $todoCategoryModel = new CategoryModel();
            $todoCategoryModel->createCategory($title, $description, $user_id);
        }
        header("Location: /todo/category");
    }

    public function edit($params){
        $this->check->requirePermission();

        $todoCategoryModel = new CategoryModel();
        $category = $todoCategoryModel->getCategoryById($params['id']);

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        if(!$category || $category['user_id'] != $user_id){
            http_response_code(404);
            include 'app/views/errors/404.php';
            return;
        }

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
            $title = trim(htmlspecialchars($_POST['title']));
            $description = trim(htmlspecialchars($_POST['description']));
            $usability  = isset($_POST['usability']) ? $_POST['usability'] : 0;

            if (empty($title) || empty($description)) {
                echo "Title and Description are required";
                return;
            }

            $todoCategoryModel = new CategoryModel();
            $todoCategoryModel->updateCategory($id, $title, $description, $usability);
        }
        header("Location: /todo/category");
    }

    public function delete($params){
        $this->check->requirePermission();

        $todoCategoryModel = new CategoryModel();
        $todoCategoryModel->deleteCategory($params['id']);

        header("Location: /todo/category");
    }
}