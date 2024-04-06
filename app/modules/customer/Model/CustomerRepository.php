<?php

namespace app\modules\customer\Model;

use app\modules\customer\Model\Customer;
use app\modules\customer\Model\CustomerResourceModel;
use app\core\Logger;

class CustomerRepository {

    private CustomerResourceModel $customerResourceModel;
    private Logger $logger;

    public function __construct(
            CustomerResourceModel $customerResourceModel,
            Logger $logger) {
        $this->customerResourceModel = $customerResourceModel;
        $this->logger = $logger;
    }

    public function getById(int $customerId): ?Customer {        
        try {
            $customer = $this->customerResourceModel->fetchCustomerById($customerId);
        } catch (\Exception $e) {            
            $this->logger->log("Error fetching customer: " . $e->getMessage());
            $customer = null;
        }
        return $customer;
    }
    
    public function getByEmail(string $customerEmail): ?Customer {        
        try {
            $customer = $this->customerResourceModel->fetchCustomerByEmail($customerEmail);
        } catch (\Exception $e) {            
            $this->logger->log("Error fetching customer: " . $e->getMessage());
            $customer = null;
        }
        return $customer;
    }

    public function getAll(): array {
        $customerDataCollection = $this->customerResourceModel->fetchAll();
        $customers = [];
        foreach ($customerDataCollection as $customerData) {
            $customers[] = $this->customerResourceModel->mapCustomerDataToModel($customerData);
        }
        return $customers;
    }    
}
