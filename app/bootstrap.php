<?php
 
session_start();

// Підключення файлу config.php 
include ROOT . '/app/etc/config.php'; 

// Автозавантаженн класів з папки /app/models/
spl_autoload_register(function ($class_name){
    include ROOT . '/app/models/' . ucfirst($class_name) . '.php';
});

// Автозавантаженн класів з папки /app/core/
spl_autoload_register(function ($class_name) {
    include ROOT . '/app/core/' . ucfirst($class_name) . '.php';
});

Route::Start(); 