<?php
session_start();

require_once 'config.php';
require_once 'autoload.php';


$router = new app\Router();
$router->run();
