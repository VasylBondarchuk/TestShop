<?php
// Check if the form is submitted
if (isset($_POST['addcustomer'])) {    
    $requiredFields = array_keys($_POST);
    array_pop($requiredFields);
    // Retrieve form data  
    $registrationFormData = Helper::getFormData($requiredFields);
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
        Helper::redirect("/category/list");
        exit;
    }
}