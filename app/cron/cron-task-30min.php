<?php
// Файл, который будет каждые 30 минут тянуть с базы задачи и отправлять в телеграм, как напоминание о сроке!
session_start();

require_once '../../config.php';
require_once '../../autoload.php';
require_once '../../functions.php';

use models\Database;
use models\telegram\TelegramBot;

$db = Database::getInstance()->getConnection();

try {
    // Поле created_at не позже 7 дней, то есть не больше либо равно 7 дням
    // Поле reminder_at соответсвует текущей дате и времени с погрешностью 15 минут.
    $query = "SELECT tr.*, tl.title, tl.finish_date, ut.telegram_chat_id, ut.telegram_username
                FROM todo_reminders AS tr
                INNER JOIN todo_list AS tl ON tr.task_id = tl.id
                INNER JOIN user_telegrams AS ut ON tr.user_id = ut.user_id
                WHERE tr.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                AND tr.reminder_at BETWEEN DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                ";
    $stmt = $db->query($query);
    $tasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    foreach($tasks as $task){
        $chatId = $task['telegram_chat_id'];
        $userTelegramName = $task['telegram_username'];
        $userTelegramId = $task['telegram_chat_id'];
        $taskTitle = $task['title'];
        $finishDate = $task['finish_date'];
        $taskId = $task['task_id'];
        $taskLink = 'https://crm.abxz.fun/todo/tasks/task/' . $taskId;

        $text = "       👋 Привет:  <b>$userTelegramName</b>
        📜Для задачи: <b>$taskTitle</b>
        ❗️ Дедлайн: <b>$finishDate</b>
        🔗Ссылка: $taskLink
        ";
        tt($text);
        $telegramBot = new TelegramBot(TEKEGRAM_BOT_API_KEY);
        $telegramBot->sendTelegramMessage($chatId, $text);

        
    }
    
    $logFile = '../../logs/cron-task-30min.php.log';
    $fp = fopen($logFile, 'a');
    $date = date('Y-m-d H:i:s');
    fwrite($fp, $date . " (cron-task-30min.php.php script - worked)\n");
    fclose($fp);

} catch (\PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}