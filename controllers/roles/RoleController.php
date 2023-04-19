<?php

namespace controllers\roles;

use models\roles\Role;
use models\Check;

class RoleController{

    private $check;

    public function __construct()
    {
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new Check($userRole);
    }

    public function index(){
        $this->check->requirePermission();

        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();

        include 'app/views/roles/index.php';
    }

    public function create(){
        $this->check->requirePermission();
        include 'app/views/roles/create.php';
    }

    public function store(){
        $this->check->requirePermission();
        if(isset($_POST['role_name']) && isset($_POST['role_description'])){
            $role_name = trim(htmlspecialchars($_POST['role_name']));
            $role_description = trim(htmlspecialchars($_POST['role_description']));

            if (empty($role_name)) {
                echo "Role name is required!";
                return;
            }

            $roleModel = new Role();
            $roleModel->createRole($role_name, $role_description);
        }
        header("Location: /roles");
    }

    public function edit($params){
        $this->check->requirePermission();
        $roleModel = new Role();
        $role = $roleModel->getRoleById($params['id']);

        if(!$role){
            echo "Role not found";
            return;
        }

        include 'app/views/roles/edit.php';
    }


    public function update($params){
        $this->check->requirePermission();

        if(isset($params['id']) && isset($_POST['role_name']) && isset($_POST['role_description'])){
            $id = trim($params['id']);
            $role_name = trim(htmlspecialchars($_POST['role_name']));
            $role_description = trim(htmlspecialchars($_POST['role_description']));

            if (empty($role_name)) {
                echo "Role name is required";
                return;
            }

            $roleModel = new Role();
            $roleModel->updateRole($id, $role_name, $role_description);
        }
        header("Location: /roles");
    }

    public function delete($params){
        $this->check->requirePermission();
        $roleModel = new Role();
        $roleModel->deleteRole($params['id']);

        header("Location: /roles");
    }
}