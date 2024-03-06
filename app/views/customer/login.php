<?php
$errors = []; // Initialize $errors variable
// Check if the login form is submitted
if (isset($_POST['login'])) {    
    $formFields = array_keys($_POST);    
    array_pop($formFields);
    // Retrieve form data  
    $loginFormData = Helper::getFormData($formFields);
    $model = $this->getModel('Customer');
    // Retrieve errors
    $errors = $model->getLoginCustomerVerificationErrors(
            $loginFormData['email'],
            $loginFormData['password']
            );  
    
    if (empty($errors)) {
        $model->loginCustomer();        
        // Redirect to a success page or login page
        Helper::redirect(INDEX_PAGE_PATH);
        exit;
    }
}
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





