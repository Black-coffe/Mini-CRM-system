<?php
require_once 'app/models/AuthUser.php';

class AuthController{

    public function register(){
        include 'app/views/users/register.php';
    }

    public function store(){
        if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
    
            if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                echo "All fields are required";
                return;
            }
    
            if ($password !== $confirm_password) {
                echo "Passwords do not match";
                return;
            }
            
            $userModel = new AuthUser();
            $userModel->register($username, $email, $password);
        }
        header("Location: index.php?page=login");
    }
    

    public function login(){
        include 'app/views/users/login.php';
    }

    public function authenticate(){
        $authModel = new AuthUser();
    
        if(isset($_POST['email']) && isset($_POST['password'])){
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $remember = isset($_POST['remember']) ? $_POST['remember'] : '';
    
            $user = $authModel->findByEmail($email);
            
            if($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
    
                if($remember == 'on'){
                    setcookie('user_email', $email, time() + (7 * 24 * 60 * 60), '/');
                    setcookie('user_password', $password, time() + (7 * 24 * 60 * 60), '/');
                }
    
                header("Location: index.php");
            } else {
                echo "Invalid email or password";
            }
        }
    }
    
    

    public function logout(){
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php');
    }

}