<?php
namespace controllers\pages;

use models\pages\PageModel;
use models\roles\Role;
use models\Check;

class PageController{

    private $check;

    public function __construct()
    {
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new Check($userRole);
    }

    public function index(){
        $this->check->requirePermission();

        $pageModel = new PageModel();
        $pages = $pageModel->getAllPages();

        include 'app/views/pages/index.php';
    }

    public function create(){
        $this->check->requirePermission();

        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();
        include 'app/views/pages/create.php';
    }

    public function store(){
        $this->check->requirePermission();

        if(isset($_POST['title']) && isset($_POST['slug']) && isset($_POST['roles'])){
            $title = trim(htmlspecialchars($_POST['title']));
            $slug = trim(htmlspecialchars($_POST['slug']));
            $roles = filter_var_array($_POST['roles'], FILTER_SANITIZE_NUMBER_INT);

            $roles = implode(",", $roles);

            if (empty($title) || empty($slug) || empty($roles)) {
                echo "Title and Slug or Role fields are required!";
                return;
            }

            $pageModel = new PageModel();
            $pageModel->createPage($title, $slug, $roles);
        }
        header("Location: /pages");
    }

    public function edit($params){
        $this->check->requirePermission();

        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();

        $pageModel = new PageModel();
        $page = $pageModel->getPageById($params['id']);

        if(!$page){
            echo "Page not found";
            return;
        }

        include 'app/views/pages/edit.php';
    }

    public function update($params){
        $this->check->requirePermission();

        if(isset($params['id']) && isset($_POST['title']) && isset($_POST['slug']) && isset($_POST['roles'])){
            $id = trim($params['id']);
            $title = trim(htmlspecialchars($_POST['title']));
            $slug = trim(htmlspecialchars($_POST['slug']));
            $roles = filter_var_array($_POST['roles'], FILTER_SANITIZE_NUMBER_INT);

            $roles = implode(",", $roles);

            if (empty($title) || empty($slug) || empty($roles)) {
                echo "Title and Slug or Role fields are required!";
                return;
            }

            $pageModel = new PageModel();
            $pageModel->updatePage($id, $title, $slug, $roles);
        }
        header("Location: /pages");
    }

    public function delete($params){
        $this->check->requirePermission();

        $pageModel = new PageModel();
        $pageModel->deletePage($params['id']);

        header("Location: /pages");
    }

}