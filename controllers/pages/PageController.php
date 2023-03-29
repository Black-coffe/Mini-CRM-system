<?php
namespace controllers\pages;

use models\pages\PageModel;

class PageController{

    public function index(){
        $pageModel = new PageModel();
        $pages = $pageModel->getAllPages();

        include 'app/views/pages/index.php';
    }

    public function create(){
        include 'app/views/pages/create.php';
    }

    public function store(){
        if(isset($_POST['title']) && isset($_POST['slug'])){
            $title = trim($_POST['title']);
            $slug = trim($_POST['slug']);

            if (empty($title) || empty($slug)) {
                echo "Title and Slug fields are required!";
                return;
            }

            $pageModel = new PageModel();
            $pageModel->createPage($title, $slug);
        }
        $path = '/'. APP_BASE_PATH . '/pages';
        header("Location: $path");
    }

    public function edit($params){
        $pageModel = new PageModel();
        $page = $pageModel->getPageById($params['id']);

        if(!$page){
            echo "Page not found";
            return;
        }

        include 'app/views/pages/edit.php';
    }

    public function update($params){
        if(isset($params['id']) && isset($_POST['title']) && isset($_POST['slug'])){
            $id = trim($params['id']);
            $title = trim($_POST['title']);
            $slug = trim($_POST['slug']);

            if (empty($title) || empty($slug)) {
                echo "Title and Slug fields are required!";
                return;
            }

            $pageModel = new PageModel();
            $pageModel->updatePage($id, $title, $slug);
        }
        $path = '/'. APP_BASE_PATH . '/pages';
        header("Location: $path");
    }

    public function delete($params){
        $pageModel = new PageModel();
        $pageModel->deletePage($params['id']);

        $path = '/'. APP_BASE_PATH . '/pages';
        header("Location: $path");
    }
}