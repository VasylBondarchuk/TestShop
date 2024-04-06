<?php

namespace app\modules\customer\Controller\Front;

ini_set('display_errors', 1);
error_reporting(E_ALL);

use app\core\Controller;
use app\core\Helper;

class Logout extends Controller {

    public function action() {
        $_SESSION = [];
        // expire cookie
        if (!empty($_COOKIE[session_name()])) {
            setcookie(session_name(), "", time() - 3600, "/");
        }
        session_destroy();
        Helper::redirect('/category/index');
    }
}
