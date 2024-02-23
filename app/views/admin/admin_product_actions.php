<?php

$customerModel = $this->getModel('Customer');
// Check if the user is logged in
if ($customerModel->isLogedIn()) {
    $customerId = $customerModel->getLoggedInCustomerId();
    // Get the customer by ID
    $customer = $customerModel->getCustomerById($customerId);
    if ($customer && $customer->isAdmin()) {
        echo '<span class="glyphicon glyphicon-pencil"></span>' . " ";
        echo Helper::urlBuilder('/product/edit', 'Edit', array('product_id' => $product->getProductId())) . " ";
        echo '<span class="glyphicon glyphicon-trash"></span>' . " ";
        echo Helper::urlBuilder('/product/delete', 'Delete', array('product_id' => $product->getProductId()));
    }
}
