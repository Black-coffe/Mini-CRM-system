<?php
require_once 'app/models/roles/Role.php';

class RoleController{

    public function index(){
        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();

        include 'app/views/roles/index.php';
    }

    public function create(){
        include 'app/views/roles/create.php';
    }

    public function store(){
        if(isset($_POST['role_name']) && isset($_POST['role_description'])){
            $role_name = trim($_POST['role_name']);
            $role_description = trim($_POST['role_description']);

            if (empty($role_name)) {
                echo "Role name is required!";
                return;
            }

            $roleModel = new Role();
            $roleModel->createRole($role_name, $role_description);
        }
        header("Location: index.php?page=roles");
    }

    public function edit($id){
        $roleModel = new Role();
        $role = $roleModel->getRoleById($id);

        if(!$role){
            echo "Role not found";
            return;
        }

        include 'app/views/roles/edit.php';
    }

    public function update(){
        if(isset($_POST['id']) && isset($_POST['role_name']) && isset($_POST['role_description'])){
            $id = trim($_POST['id']);
            $role_name = trim($_POST['role_name']);
            $role_description = trim($_POST['role_description']);

            if (empty($role_name)) {
                echo "Role name is required";
                return;
            }

            $roleModel = new Role();
            $roleModel->updateRole($id, $role_name, $role_description);
        }
        header("Location: index.php?page=roles");
    }

    public function delete(){
        $roleModel = new Role();
        $roleModel->deleteRole($_GET['id']);

        header('Location: index.php?page=roles');
    }
}