<?php

namespace controllers\shortlink;

use models\shortlink\ShortLinkModel;
use models\roles\Role;
use models\Check;

class ShortLinkController {

    private $shortLinkModel;
    private $check;
    private $userId;
    private $domain;

    public function __construct() {
        $this->shortLinkModel = new ShortLinkModel();
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $this->check = new Check($userRole);
        $this->domain = $_SERVER['SERVER_NAME'];
    }
    
    public function index(){
        // $this->check->requirePermission();
        
        $short_links = $this->shortLinkModel->getAllShortLinksByIdUser($this->userId);
        $domain = $this->domain;

        include 'app/views/shortlink/index.php';
    }

    public function create() {
        // $this->check->requirePermission();
        $userId = $this->userId;
        
        include 'app/views/shortlink/create.php';
    }

    public function store() {
        // $this->check->requirePermission();

        if(isset($_POST['original_url']) && isset($_POST['user_id']) && isset($_POST['title_link'])){
            $original_url = trim(htmlspecialchars($_POST['original_url']));
            $user_id = $_POST['user_id'];
            $title_link = $_POST['title_link'];

           // Проверяем, является ли URL действительным
           if (!filter_var($original_url, FILTER_VALIDATE_URL)) {
                echo "Invalid URL!";
                return;
            } 

            //Проверяем наличие шорт кода в массиве, если нет - генерируем!
            if (!$_POST['short_code']) {
                $shortCode = '';
                while(strlen($shortCode) < 6){
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    $randomString = '';
                    for($i = 0; $i < rand(6, 10); $i++){
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                    }
                    $shortCode = substr(preg_replace('/[^a-zA-Z\d]/', '', $randomString), 0, rand(6, 10));
                    // Проверка шорт кода
                    if (!preg_match('/^[a-zA-Z][a-zA-Z\d-]{5,9}$/', $shortCode)) {
                        $shortCode = '';
                    }
                }
            }else{
                $shortCode = $_POST['short_code'];
            }

            // Делаем проверку в базе, есть ли такой код уже записан, генерируем по новой
            while ($this->shortLinkModel->isShortUrlExists($shortCode)) {
                $shortCode = '';
                while (strlen($shortCode) < 6) {
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    $randomString = '';
                    for ($i = 0; $i < rand(6, 10); $i++) {
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                    }
                    $shortCode = substr(preg_replace('/[^a-zA-Z\d]/', '', $randomString), 0, rand(6, 10));
                    // Проверка короткого кода
                    if (!preg_match('/^[a-zA-Z][a-zA-Z\d-]{5,9}$/', $shortCode)) {
                        $shortCode = '';
                    }
                }
            }
            
            // Запись в БД
            $shortUrlId = $this->shortLinkModel->createLink($title_link, $_POST['original_url'], $shortCode);
            $this->shortLinkModel->createUserLink($user_id, $shortUrlId);
        }
        header("location: /shortlink");
    }


    public function redirect(){
        
        // Определяем протокол (http или https)
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        // Получаем доменное имя сервера
        $domain = $_SERVER['HTTP_HOST'];
        // Получаем URI (часть URL-адреса после доменного имени)
        $uri = $_SERVER['REQUEST_URI'];

        $url = $protocol . "://" . $domain . $uri;

        // Извлекаем код из URL-адреса
        $code = basename(parse_url($url, PHP_URL_PATH));

        // Получаем оригинальную ссылку по сокращенному коду
        $originalUrl = $this->shortLinkModel->getOriginalLinkByShortCode($code);
        
        header("location: $originalUrl");
        
    }

}
