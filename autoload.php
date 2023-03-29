<?php
spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $classPath = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';

    if (file_exists($classPath)) {
        require_once $classPath;
    } else {
        $class = str_replace('controllers', 'app/controllers', $class);
        $classPath = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';

        if (file_exists($classPath)) {
            require_once $classPath;
        } else {
            throw new Exception("Class {$class} not found in {$classPath}");
        }
    }
});

