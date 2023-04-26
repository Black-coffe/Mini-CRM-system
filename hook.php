<?php

session_start();

require_once 'config.php';
require_once 'autoload.php';
require_once 'functions.php';
require_once 'models/telegram/TelegramBot.php';

use models\Check;
use models\users\User;
use models\telegram\TelegramBot;

$botApiKey = '5927918632:AAGM_DWox57PI_0VdEAtorOBOnaL6eNTPNU';
$telegramBot = new TelegramBot($botApiKey);

$content = file_get_contents('php://input');
$update = json_decode($content, true);

$telegramBot->handleUpdate($update);
