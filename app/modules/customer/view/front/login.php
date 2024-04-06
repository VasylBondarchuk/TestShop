<?php

use app\core\FormGenerator;
$errors = $viewModel ? $viewModel->getCustomerLoginErrors() : [];
require_once FORMS_HANDLER_PATH . DS . 'customer_login_fields.php';
?>    

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/var/www/html/css/style.css">
    <title>Log in</title>
</head>
<body>
    <center><h2> Log in </h2></center>
    <form method="POST" class="login-form">
        <div class="container">
            <?= FormGenerator::generateFields($customerLoginFormFields, $errors); ?>
            <input class="button" type="submit" name="login" value="Log in">
        </div>
    </form>
</body>
</html>





