<?php

function tt($str){
    echo "<pre>";
        print_r($str);
    echo "</pre>";
}
function tte($str){
    echo "<pre>";
        print_r($str);
    echo "</pre>";
    exit();
}
// config.php

define('APP_BASE_PATH', 'crm_for_telegram');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'crm_for_telegram');

define('START_ROLE', 1);

