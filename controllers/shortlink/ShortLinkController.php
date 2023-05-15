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

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $domain = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $url = $protocol . "://" . $domain . $uri;

        $code = basename(parse_url($url, PHP_URL_PATH));
        $originalUrl = $this->shortLinkModel->getOriginalLinkByShortCode($code);
        
        // Работаем с сохранением статистики и данных

        $data['ip_user'] = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : null;
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ? $_SERVER['HTTP_USER_AGENT']: null;
        $data['user_referer'] = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER']: null;

        $shortCode = $_SERVER['REQUEST_URI'];
        $shortCode = trim($shortCode, '/');

        if($shortCode){
            $shortLinkId = $this->shortLinkModel->getIdlLinkByShortCode($shortCode);
            $data['short_link_id'] = $shortLinkId;

            // ЗАписываем данные о пользователе в отдельную таблицу
            $this->shortLinkModel->createUserInfoByRedirectAction($data);
            // Считаем количество переходов
            $existingRow = $this->shortLinkModel->getByShortLinksId($shortLinkId);
            if ($existingRow) {
                $this->shortLinkModel->updateAmount($existingRow['id'], $existingRow['amount'] + 1);
            } else {
                $this->shortLinkModel->createNewRow($shortLinkId, 1);
            }
        }

        header("location: $originalUrl");
        
    }

    public function delete($params){
        // $this->check->requirePermission();
        $user_id = $this->userId;
        $this->shortLinkModel->deleteShortLink($params['id'], $user_id);

        header("Location: /shortlink");
    }

    public function edit($params) {
        // $this->check->requirePermission();
        $user_id = $this->userId;
        $short_link = $this->shortLinkModel->getShortLinkById($params['id'], $user_id);
        
        include 'app/views/shortlink/edit.php';
    }

    public function update() {
        // $this->check->requirePermission();
       
        if(isset($_POST['original_url']) && isset($_POST['short_link_id']) && isset($_POST['title_link'])){
            $original_url = trim(htmlspecialchars($_POST['original_url']));
            $short_link_id = $_POST['short_link_id'];
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
           
            // Делаем проверку в базе, есть ли такой код и он принадлежит другому пользователю, генерируем по новой
            while ($this->shortLinkModel->isShortUrlExistsWithIdAndCode($short_link_id, $shortCode)) {
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
            $this->shortLinkModel->updateLink($short_link_id, $title_link, $original_url, $shortCode);
        }
        header("location: /shortlink");
    }

    public function information($params){
        // $this->check->requirePermission();
        $informations = $this->shortLinkModel->getInformationAboutEveryClick($params['id']);
        include 'app/views/shortlink/information.php';
    }

}
