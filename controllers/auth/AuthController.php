<?php
namespace controllers\auth;

use models\AuthUser;

class AuthController{

    public function register(){
        include 'app/views/auth/register.php';
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
        header("Location: APP_BASE_PATH . '/auth/login'");
    }
    

    public function login(){
        include 'app/views/auth/login.php';
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
                $_SESSION['user_email'] = $user['email'];
    
                if($remember == 'on'){
                    setcookie('user_email', $email, time() + (7 * 24 * 60 * 60), '/');
                    setcookie('user_password', $password, time() + (7 * 24 * 60 * 60), '/');
                }
    
                $path = '/'. APP_BASE_PATH;
                header("Location: $path");
            } else {
                echo "Invalid email or password";
            }
        }
    }
    
    

    public function logout(){
        session_start();
        session_unset();
        session_destroy();
        $path = '/'. APP_BASE_PATH;
        header("Location: $path");
    }

}