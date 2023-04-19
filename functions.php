<?php
// Проверка активного пункта меню
function is_active($path) {
    $currentPath = $_SERVER['REQUEST_URI'];
    return $path === $currentPath ? 'active' : '';
}


// генерация одноразового OTP пароля для привязки телеграм аккаунта
function generateOTP(){
    $opt = rand(1000000, 9999999);
    return $opt;
}

?>
