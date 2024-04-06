<?php
// app\modules\customer\Factory\CustomerLoginViewModelFactory.php
namespace app\modules\customer\Factory;

use app\modules\customer\Model\CustomerResourceModel;
use app\core\Logger;
use app\modules\customer\Model\CustomerRepository;

class CustomerRepositoryFactory
{
    public static function create(): CustomerRepository
    {        
        $customerResourceModel = new CustomerResourceModel();
        $logger = new Logger();        
        return new CustomerRepository($customerResourceModel, $logger);
    }
}
