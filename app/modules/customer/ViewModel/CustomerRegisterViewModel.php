<?php

// app\modules\product\ViewModel\ProductIndexViewModel.php
namespace app\modules\customer\ViewModel;

use app\modules\customer\Model\Customer;
use app\core\Helper;

class CustomerRegisterViewModel {

    private Customer $customer;

    public function __construct(Customer $customer) {
        $this->customer = $customer;
    }

    public function getRegistrationCustomerErrors(): array {
        $errors = $this->customer->getRegistrationCustomerErrors(
                $this->getRegistrationFormData());
        return $errors;
    }
    
    public function getRegistrationFormData(): array {
        $formFields = array_keys($_POST);
            array_pop($formFields);
            // Retrieve form data  
            $registrationFormData = Helper::getFormData($formFields);
            return $registrationFormData;
    }  
}
