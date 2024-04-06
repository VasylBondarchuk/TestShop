<?php

// app\modules\product\ViewModel\ProductIndexViewModel.php
namespace app\modules\customer\ViewModel;

use app\modules\customer\Model\Customer;
use app\core\Helper;

class CustomerLoginViewModel {

    private Customer $customer;

    public function __construct(Customer $customer) {
        $this->customer = $customer;
    }

    public function getCustomerLoginErrors(): array {
        $errors = []; 
        $formFields = array_keys($_POST);
        array_pop($formFields);        
        $loginFormData = Helper::getFormData($formFields);        
        $errors = $this->customer->getLoginCustomerVerificationErrors(
                $loginFormData['email'],
                $loginFormData['password']
        );
        return $errors;
    }
}
