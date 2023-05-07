<?php

namespace models\telegram;

use models\users\User;
use models\telegram\CommandHandler;


class TelegramBot {
    private $botApiKey;

    public function __construct($botApiKey) {
        $this->botApiKey = $botApiKey;
    }

    public function sendTelegramMessage($chatId, $text) {
        $url = "https://api.telegram.org/bot{$this->botApiKey}/sendMessage";
        $postData = [
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' => $text
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function sendTelegramQuizMessage($data, $headers = []) {
        $url = "https://api.telegram.org/bot{$this->botApiKey}/sendPoll";
        $postData = [
            'chat_id' => $data['chat_id'],
            'parse_mode' => 'HTML',
            'question' => $data['question'],
            'options' => json_encode($data['options']),
            'is_anonymous' => $data['is_anonymous'], // True & False
            'allows_multiple_answers' => $data['allows_multiple_answers'], // True & False
            'type' => 'quiz',
            'correct_option_id' => $data['correct_option_id'],
            'explanation' => $data['explanation']
        ];
    
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => array_merge(["Content-Type: application/json"], $headers)
        ]);
    
        $result = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
    
        if ($result === false) {
            throw new \Exception('Error sending Telegram quiz message: ' . $error);
        }
    
        return json_decode($result, true);
    }
    
    

    public function handleUpdate($update) {
        if (!isset($update['message'])) {
            return;
        }

        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $text = $message['text'];
        $username = $message['from']['username'];

        $userModel = new User();
        $commandHandler = new CommandHandler();

        try {
            $userState = $userModel->getUserState($chatId);
            $currentState = $userState ? $userState['state'] : '';
            $user_id = $userState ? $userState['user_id'] : null;

            switch ($text) {
                case '/start':
                    $response = $commandHandler->handleStartCommand($chatId);
                    $userModel->setUserState($chatId, 'email');
                    break;
                case '/addaccount':
                    $response = $commandHandler->handleEmailCommand($chatId);
                    $userModel->setUserState($chatId, 'email');
                    break;
                case '/help':
                    $response = $commandHandler->handleHelpCommand($chatId);
                    break;
                case '/task':
                    $response = $commandHandler->handleTaskCommand($chatId);
                    break;
                default:
                    $response = $this->handleMessage($text, $currentState, $chatId, $userModel, $user_id, $username);
            }
        } catch (\Exception $e) {
            error_log("Error: " . $e->getMessage() . "\n", 3, 'logs/error.log');
            $response = 'Произошла ошибка. Пожалуйста, попробуйте еще раз.';
        }

        $this->sendTelegramMessage($chatId, $response);
    }

    private function handleMessage($text, $currentState, $chatId, $userModel, $user_id, $username) {
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
                $response = 'Ваш код подтвержден, и ваш аккаунт связан с вашим Telegram!';
                $userModel->setUserState($chatId, ''); // Очищаем состояние
            } else {
                $response = 'Введенный код неверен или ...';
            }
        } else {
            $response = 'Я не понимаю вашу команду. ' . $currentState;
        }
        return $response;
    }

}