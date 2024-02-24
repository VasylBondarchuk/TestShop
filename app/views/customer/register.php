<style>
    .warning{
        color: LightSeaGreen ;
    }
    .error {
        color: #FF0000;
    }
    .container {
        width: 50%;
        clear: both;
    }
    .container input {
        width: 100%;
        clear: both;
    }
    .button {
        font-size: 25px;
        border: none;
        padding: 15px 32px;
        background-color: LightSeaGreen ;
        color: white;
        border: 2px solid LightSeaGreen ;
    }
</style>

<center><h2> Реєстрація на сайті </h2></center>

<?php
// Check if the form is submitted
if (isset($_POST['addcustomer'])) {    
    $formFields = array_keys($_POST);
    array_pop($formFields);
    // Retrieve form data  
    $registrationFormData = Helper::getFormData($formFields);
    // Retrieve errors
    $errors = FormValidator::validateRegistrationForm($registrationFormData);   
    
    if (empty($errors)) {
        // Validation passed, proceed with registration
        $model = $this->getModel('Customer');
        $model->registerCustomer($registrationFormData);
        $customer = $model->getCustomerById($model->getLastId()); 
        // Log in the customer after successful registration
        $customer->loginCustomer();
        // Redirect to a success page or login page
        Helper::redirect(INDEX_PAGE_PATH );
        exit;
    }
}
require_once FORMS_HANDLER_PATH . DS . 'customer_register_fields.php';

?>


<form method="POST">
    <div class="container">
        <?= FormGenerator::generateFields($customerRegisterFormFields); ?>
        <input class="button" type="submit" name="addcustomer" value="Зареєструватися">
    </div>
</form>
