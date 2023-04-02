<?php
// Проверка активного пункта меню
function is_active($path) {
    $currentPath = $_SERVER['REQUEST_URI'];
    return $path === $currentPath ? 'active' : '';
}



?>
