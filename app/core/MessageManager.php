<?php

class MessageManager {
    
    const ERROR = 'error';
    const WARNING = 'warning';
    const INFO = 'info';
    
    public static function setError($message) {
        $_SESSION[self::ERROR] = $message;
        header("Refresh:0; url=".$_SERVER['PHP_SELF']);
    }
    
    public static function setWarning($message) {
        $_SESSION[self::WARNING] = $message;
        header("Refresh:0; url=".$_SERVER['PHP_SELF']);
    }
    
    public static function setInfo($message) {
        $_SESSION[self::INFO] = $message;
        header("Refresh:0; url=".$_SERVER['PHP_SELF']);
    }
    
    public static function getError() {
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']); // Clear the error message
            return $error;
        }
        return null;
    }

    public static function getWarning() {
        if (isset($_SESSION['warning'])) {
            $warning = $_SESSION['warning'];
            unset($_SESSION['warning']); // Clear the warning message
            return $warning;
        }
        return null;
    }

    public static function getInfo() {
        if (isset($_SESSION['info'])) {
            $info = $_SESSION['info'];
            unset($_SESSION['info']); // Clear the info message
            return $info;
        }
        return null;
    }
}
