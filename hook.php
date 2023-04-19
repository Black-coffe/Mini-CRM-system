<?php

require_once 'vendor/autoload.php';

use Telegram\Bot\Api;

$botApiKey = '5927918632:AAGM_DWox57PI_0VdEAtorOBOnaL6eNTPNU';
$botUsername = '@mini_crm_bot';

$telegram = new Api($botApiKey);

$update = $telegram->getWebhookUpdates();

$chatId = $update->getMessage()->getChat()->getId();
$text = $update->getMessage()->getText();
$username = $update->getMessage()->getFrom()->getUsername();

// Создаем папку logs, если она еще не создана для наших логов
if(!file_exists('logs')){
    mkdir('logs', 0755, true);
}

// Задаем имя файла с текущим годом и месяцем
$logFileName = sprintf('logs/%s_telegram_bot_user_messages.log', date('Y_m'));

// Записываем лог с информацией об обращении
$logMessage = sprintf(
    "[%s] User: %s (ID: %d) sent message: %s\n",
    date('Y-m-d H:i:s'),
    $username,
    $chatId,
    $text
);

error_log($logMessage, 3, $logFileName);


switch ($text) {
    case '/start':
        $response = 'Добро пожаловать в наш телеграм-бот! Наш бот умеет многое, но чтобы использовать его функционал вам нужно пройти валидацию:
        Перейдите по ссылке https://crm.abxz.fun/ и авторизуйтесь, зайдите в настройки (низ слева) и нажмите на пункт меню "';
        break;
    case '/validate':
        $response = 'Это команда для проверки!';
        break;
    default:
        $response = 'Я не понимаю вашу команду.';
}

$telegram->sendMessage([
    'chat_id' => $chatId,
    'text' => $response
]);