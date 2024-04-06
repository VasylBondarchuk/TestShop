<?php
// app\modules\customer\Factory\CustomerLoginViewModelFactory.php
namespace app\modules\customer\Factory;

use app\modules\customer\ViewModel\CustomerLoginViewModel;
use app\modules\customer\Model\Customer;

class CustomerLoginViewModelFactory
{
    public static function create(): CustomerLoginViewModel
    {
        $customerModel = new Customer();        
        return new CustomerLoginViewModel($customerModel);
    }
}
