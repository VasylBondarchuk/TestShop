<?php
namespace app\modules\customer\Model;

use app\core\ResourceModel;
use app\modules\customer\Model\Customer;
use app\core\DataMapper;

class CustomerResourceModel extends ResourceModel
{  
    public function __construct()
    {   
        parent::__construct(
                Customer::TABLE_NAME,
                Customer::CUSTOMER_ID
                );
    }

    public function getCustomerCollection() : array
    {        
        $customersData = $this->fetchAll();        
        $customers = [];
        foreach ($customersData as $customerData) {
            $customer = $this->mapCustomerDataToModel($customerData);
            $customers[] = $customer;
        }
        return $customers;
    } 
    
    public function fetchCustomerById(int $customerId): ?Customer
    {
        $customerData = $this->fetchById($customerId);
        if (!$customerData) {
            return null;
        }        
        return $this->mapCustomerDataToModel($customerData);
    }
    
     public function fetchCustomerByEmail(string $customerEmail): ?Customer
    {
        $customerData = $this->fetchByParam(Customer::EMAIL, $customerEmail);
        if (!$customerData) {
            return null;
        }        
        return $this->mapCustomerDataToModel($customerData);
    }

    private function mapCustomerDataToModel(array $customerData): Customer
    {        
        $customer = new Customer();
        DataMapper::mapDataToObject($customerData, $customer);           
        return $customer;
    }
}
