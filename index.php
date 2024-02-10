<?php

error_reporting(E_ERROR | E_PARSE);
// Декларування константи ROOT - адреса директорії, де розміщено файл index.php (директрія проекту) 
define("ROOT", getcwd());
// Декларування константи DS - розділювач шляху
define("DS", DIRECTORY_SEPARATOR);
// підключення файлу bootstrap.php
include ROOT . '/app/bootstrap.php';