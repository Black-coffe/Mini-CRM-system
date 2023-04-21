<?php

session_start();

require_once 'config.php';
require_once 'autoload.php';
require_once 'functions.php';
require_once 'models/TelegramBot.php';

use models\Check;
use models\users\User;
use models\TelegramBot;

$botApiKey = TEKEGRAM_BOT_API_KEY;
$telegramBot = new TelegramBot($botApiKey);

$content = file_get_contents('php://input');
$update = json_decode($content, true);

$telegramBot->handleUpdate($update);
