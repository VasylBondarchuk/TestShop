<?php
 
session_start();

// Підключення файлу config.php 
include_once ROOT . DS . 'app' . DS . 'etc' . DS . 'config.php'; 

// Автозавантаженн класів з папки /app/models/
spl_autoload_register(function ($class_name){
    include_once ROOT . DS .'app' . DS . 'models' . DS . ucfirst($class_name) . '.php';
});

// Автозавантаженн класів з папки /app/core/
spl_autoload_register(function ($class_name) {
    include_once ROOT . DS . 'app' . DS . 'core' . DS . ucfirst($class_name) . '.php';
});

Route::Start(); 