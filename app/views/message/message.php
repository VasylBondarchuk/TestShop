<?php

// Include or autoload the MessageManager class

// Retrieve and display an error message
$error = MessageManager::getError();
if ($error) {
    echo "<div class='error-box'>$error</div>";
}

// Retrieve and display a warning message
$warning = MessageManager::getWarning();
if ($warning) {
    echo "<div class='warning-box'>$warning</div>";
}

// Retrieve and display an info message
$info = MessageManager::getInfo();
if ($info) {
    echo "<div class='info-box'>$info</div>";
}

