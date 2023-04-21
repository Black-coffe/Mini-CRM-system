<?php
// Проверка активного пункта меню
function is_active($path) {
    $currentPath = $_SERVER['REQUEST_URI'];
    return $path === $currentPath ? 'active' : '';
}

// Генерация одноразового пароля для профайла пользователя под телеграм
function generateOTP() {
    $otp = rand(1000000, 9999999);
    return $otp;
}


?>
