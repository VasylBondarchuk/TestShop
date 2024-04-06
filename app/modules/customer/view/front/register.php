<?php
use app\core\FormGenerator;
$errors = $viewModel->getRegistrationCustomerErrors();
require_once FORMS_HANDLER_PATH . DS . 'customer_register_fields.php';
?>


<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/var/www/html/css/style.css">
    <title>Registration</title>
</head>
<body>
    <center><h2>Registration</h2></center>
    <form method="POST" class="login-form">
        <div class="container">
            <?= FormGenerator::generateFields($customerRegisterFormFields, $errors); ?>
            <input class="button" type="submit" name="addcustomer" value="Register">
        </div>
    </form>
</body>
</html>
