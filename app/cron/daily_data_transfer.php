<?php

session_start();

require_once '../../config.php';
require_once '../../autoload.php';
require_once '../../functions.php';

use models\Database;


$db = Database::getInstance()->getConnection();

try{
    //Получение всех задач с полем `reminder_at` равным или меньше чем 7 дней
    $query = "SELECT * FROM todo_list WHERE reminder_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)";
    $stmt = $db->query($query);
    $tasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // Записываемзадачи в таблицу `todo_reminders`
    $insertQuery = "INSERT INTO 
        todo_reminders (user_id, task_id, reminder_at) 
        SELECT :user_id, :task_id, :reminder_at 
        FROM dual 
        WHERE 
            NOT EXISTS (SELECT * FROM todo_reminders WHERE task_id = :task_id)";

    $insertStmt = $db->prepare($insertQuery);

    foreach($tasks as $task){
        $insertStmt->bindParam(':user_id', $task['user_id'], \PDO::PARAM_INT);
        $insertStmt->bindParam(':task_id', $task['id'], \PDO::PARAM_INT);
        $insertStmt->bindParam(':reminder_at', $task['reminder_at']);
        $insertStmt->execute();
    }
        $logFile = '../../logs/daily_data_transfer.log';
        $fp = fopen($logFile, 'a');
        $date = date('Y-m-d H:i:s');
        fwrite($fp, $date . " (daily_data_transfer.php script - worked)\n");
        fclose($fp);

} catch(\PDOException $e){
    echo "Ошибка: " . $e->getMessage();
}