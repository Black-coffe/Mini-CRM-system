<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function tt($str){
    echo "<pre>";
        print_r($str);
    echo "</pre>";
}
function tte($str){
    echo "<pre>";
        print_r($str);
    echo "</pre>";
    exit();
}
// config.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'crm_for_telegram');

define('ENABLE_PERMISSION_CHECK', true); // Установите значение в false, чтобы отключить проверки разрешений

define('TEKEGRAM_BOT_API_KEY', '');

//За сколько дней будет напоминание о задаче по умолчанию. если пользователь не исправит это в редактировании задачи
define('REMINDER_DATA', ' +1 day');

//Телеграм ID чат для вопросов
define('TELEGRAM_CHAT_ID', '');


