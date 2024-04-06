<?php
// app\modules\customer\Factory\CustomerLoginViewModelFactory.php
namespace app\modules\customer\Factory;

use app\modules\customer\ViewModel\CustomerRegisterViewModel;
use app\modules\customer\Model\Customer;

class CustomerRegisterViewModelFactory
{
    public static function create(): CustomerRegisterViewModel
    {
        $customerModel = new Customer();        
        return new CustomerRegisterViewModel($customerModel);
    }
}
