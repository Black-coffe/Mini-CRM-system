<?php
session_start();

require_once '../../config.php';
require_once '../../autoload.php';
require_once '../../functions.php';

use models\Database;
use models\telegram\TelegramBot;
use models\quiz\QuizModel;

$db = Database::getInstance()->getConnection();

try{
    // Делаем рандомную выборку одного вопроса с базы quiz_questions
    $quizModel = new QuizModel();
    $quiz  = null;

    // Проверка был ли такой вопрос за период (180 дней)
    $amountOfTime = date('Y-m-d H:i:s', strtotime('-180 days'));

    while(!$quiz){
        $randomQuiz  = $quizModel->getRandomQuiz();
        $count = $quizModel->checkTelegramQuizQuestion($randomQuiz['id'], $amountOfTime);

        if($count == 0){
            $quiz = $randomQuiz;
        }
    }

    $answers = [$quiz['answer_1'],$quiz['answer_2'],$quiz['answer_3']];

    $dataSet = [
        'chat_id' => TELEGRAM_CHAT_ID,
        'question' => $quiz['question'],
        'options' => $answers,
        'is_anonymous' => true,
        'allows_multiple_answers' => false,
        'correct_option_id' => $quiz['correct_answer'],
        'explanation' => $quiz['explanation']
    ];


    $telegramBot = new TelegramBot(TEKEGRAM_BOT_API_KEY);
    $telegramBot->sendTelegramQuizMessage($dataSet);

    $quizModel->writeInTelegramQuizQuestions($quiz['id']);

}catch (\PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}

// https://api.telegram.org/bot{{YOUR_TOKEN}}/getUpdates
// Ccылка для того, чтобы узнать какой ID в канала или группы телеграм
// 1- создать бота, 2- добавить его в группу, 3- написать текст в группе, 4- посмотреть ID группы через ссылку выше


