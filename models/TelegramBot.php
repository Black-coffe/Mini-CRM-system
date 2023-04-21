<?php

namespace models;

use models\users\User;

class TelegramBot {
    private $botApiKey;

    public function __construct($botApiKey) {
        $this->botApiKey = $botApiKey;
    }


    // Метод для отправки сообщения в чат с указанным ID и текстом
    public function sendTelegramMessage($chatId, $text) {
        // Формирование URL для запроса к API телеграм
        $url = "https://api.telegram.org/bot{$this->botApiKey}/sendMessage";
        // Формирование данных для POST запроса
        $postData = [
            'chat_id' => $chatId,
            'text' => $text
        ];

        // Инициализация сеанс cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        // Выполенение запроса cURL
        $response = curl_exec($ch);
        curl_close($ch);

        // Декодируем строку и отдаем через return
        return json_decode($response, true);
    }

    // Метод для обработки входящих обновлений от Telegram
    public function handleUpdate($update) {
        // Если сообщения нет, остановить обработку
        if(!isset($update['message'])){
            return;
        }

        // Получаем данные из сообщения
        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $text = $message['text'];
        $username = $message['from']['username'];

        $userModel = new User();

        try{
            // Получить текущее состояние пользователя
            $userState = $userModel->getUserState($chatId);
            $currentState = $userState ? $userState['state'] : '';
            $user_id = $userState ? $userState['user_id'] : null;

            // Обрабатываем команды и текстовые сообщения
            switch ($text) {
                // Если команда /start, вызываем обработчик handleStartCommand и устанавливаем состояние пользователя на 'email'
                case '/start':
                    $response = $this->handleStartCommand($chatId);
                    $userModel->setUserState($chatId, 'start');
                    break;
                // Если команда /email, вызываем обработчик handleEmailCommand и устанавливаем состояние пользователя на 'email'
                case '/email':
                    $response = $this->handleEmailCommand($chatId);
                    $userModel->setUserState($chatId, 'email');
                    break;
                // Если команда /help, вызываем обработчик handleHelpCommand
                case '/help':
                    $response = $this->handleHelpCommand($chatId);
                    break;
                // Если другая команда или текстовое сообщение, вызываем обработчик handleMessage с передачей параметров
                default:
                    $response = $this->handleMessage($text, $currentState, $chatId, $userModel, $user_id, $username);
            }
        } catch(\Exception $e){
            error_log("Error: " . $e->getMessage() . "\n", 3, 'logs/error.log');
            $response = 'Произошла ошибка. Пожалуйста, попробуйте еще раз.';
        }

        $this->sendTelegramMessage($chatId, $response);
    }


    private function handleMessage($text, $currentState, $chatId, $userModel, $user_id, $username)
    {
        if ($currentState === 'email') {
            $user = $userModel->getUserByEmail($text);

            if ($user) {
                $user_id = $user['id'];
                $response = 'Теперь введите код OTP, ...';
                $userModel->setUserState($chatId, 'otp', $user_id);
            } else {
                $response = 'Пользователь с таким email не найден. ...';
            }
        } elseif ($currentState === 'otp' && preg_match('/^\d{7}$/', $text)) {
            $otpCode = intval($text);
            $otpInfo = $userModel->getOtpInfoByUserIdAndCode($user_id, $otpCode);

            if ($otpInfo) {
                $userModel->createUserTelegram($user_id, $chatId, $username);
                $response = 'Ваш код подтвержден, и ваши аккаунты связаны!!!';
                $userModel->setUserState($chatId, ''); // Очищаем состояние
            } else {
                $response = 'Введенный код неверен или ...';
            }
        } else {
            $response = 'Я не понимаю вашу команду. ' . $currentState;
        }
        return $response;
    }


    // Ниже методы, которые отвечают за обработку команд c телеграма
    private function handleHelpCommand($chatId) 
    {
        return "Список команд:\n/start - начать работу\n/email - ввести email\n/help - вывести справку";
    }

    private function handleEmailCommand($chatId) 
    {
        return "Введите email с вашего аккаунта miniCRM...";
    }

    private function handleStartCommand($chatId) 
    {
        return "Чтобы иметь возможность пользоваться нашим ботом, вам необходимо провести привязку аккаунта телеграм и аккаунта в miniCRM. Для инструкции перейдите по адресу https://crm.abxz.fun, авторизуйтесь в системе и перейдите в ваш профайл...";
    }

    // еще какие-то методы
}