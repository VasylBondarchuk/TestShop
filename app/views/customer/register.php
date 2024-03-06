<?php
$errors = []; // Initialize $errors variable
// Check if the form is submitted
if (isset($_POST['addcustomer'])) {    
    $formFields = array_keys($_POST);
    array_pop($formFields);
    // Retrieve form data  
    $registrationFormData = Helper::getFormData($formFields);
    $model = $this->getModel('Customer');
    $errors = $model->getRegistrationCustomerErrors($registrationFormData);
    if (empty($errors)) { 
        $model->registerCustomer($registrationFormData);
        $customer = $model->getCustomerById($model->getLastId()); 
        // Log in the customer after successful registration
        $customer->loginCustomer();        
        // Redirect to a success page or login page
        Helper::redirect(INDEX_PAGE_PATH);
        $_SESSION['error'] = 'You have registered succesfully!';
        exit;
    }
}
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
