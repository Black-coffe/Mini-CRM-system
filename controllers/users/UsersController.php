<?php
namespace controllers\users;

use models\roles\Role;
use models\User;
class UsersController{

    public function index(){
        $userModel = new User();
        $users = $userModel->readAll();

        include 'app/views/users/index.php';
    }

    public function create(){
        include 'app/views/users/create.php';
    }

    public function store(){
        if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                echo "Passwords do not match";
                return;
            }

            $userModel = new User();
            $data = [
              'username' => $_POST['username'],
              'email' => $_POST['email'],
              'password' => $password,
              'role' => 1, 
            ];
            $userModel->create($data);
            
        }
        $path = '/'. APP_BASE_PATH . '/users';
        header("Location: $path");
    }

    public function edit($params){
        $userModel = new User();
        $user = $userModel->read($params['id']);
    
        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();
    
        include 'app/views/users/edit.php';
    }
    

    public function update($params){
        $userModel = new User();
        $userModel->update($params['id'], $_POST);
        $path = '/'. APP_BASE_PATH . '/users';
        header("Location: $path");
    }


    public function delete($params){
        $userModel = new User();
        $userModel->delete($params['id']);
        $path = '/'. APP_BASE_PATH . '/users';
        header("Location: $path");
    }

}