<?php
// Методы обработки запросов с телеграмма
namespace models\telegram;


use models\users\User;
use models\todo\tasks\TaskModel;

class CommandHandler
{

    public static function handleHelpCommand()
    {
        return "Список команд:\n/start - начать работу\n/addaccount - привязка телеграмма\n/task - статусы по задачам";
    }
    
    public static function handleEmailCommand()
    {
        return "Введите email с вашего аккаунта miniCRM...";
    }
    
    public static function handleStartCommand()
    {
        return "Чтобы иметь возможность пользоваться нашим ботом, вам необходимо провести привязку аккаунта телеграм и аккаунта в miniCRM. Для инструкции перейдите по адресу https://crm.abxz.fun, авторизуйтесь в системе и перейдите в ваш профайл... \n/help - список команд";
    }

    public static function handleTaskCommand($chatId)
    {
        $userModel = new User();
        $userTelegram = $userModel->getUserByTelegramChatId($chatId);
        $user_id = $userTelegram['user_id'];

        $taskModel = new TaskModel();
        $tasks = $taskModel->getTasksCountAndStatusByUserId($user_id);
        $tasks = json_encode($tasks);
        $tasks = json_decode($tasks, true);
        $obj = $tasks[0];

        $userTelegram = $userTelegram['telegram_username'];
        $allTasks = $obj['all_tasks'];
        $completed = $obj['completed'];
        $expired = $obj['expired'];
        $opened = $obj['opened'];

        $text = "   👋 Привет:  <b>$userTelegram</b>
        📜 Всего задач: <b>$allTasks</b>
        📌 Закрытых: <b>$completed</b>
        ❗️ Просроченных: <b>$expired</b>
        📖 Открытых: <b>$opened</b>
        ";

        return  $text;
    }

}
