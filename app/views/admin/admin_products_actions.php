<?php
$customerModel = $this->getModel('Customer');

// Check if the user is logged in
if ($customerModel->isLogedIn()) {
    $customerId = $customerModel->getLoggedInCustomerId();
    
    // Get the customer by ID
    $customer = $customerModel->getCustomerById($customerId);

    // Check if the retrieved customer is an admin
    if ($customer && $customer->isAdmin()) {
        ?>
        <div class="product">
            <p>
                <span class="glyphicon glyphicon-plus"></span>
                <?= Helper::urlBuilder('/product/add', 'Додати товар');?>
            </p>
        </div>
        <div class="product">
            <p>
                <span class="glyphicon glyphicon-export"></span>
                <?= Helper::urlBuilder('/product/unload', 'Експорт');?>
            </p>
        </div>
        <div class="product">
            <p>
                <span class="glyphicon glyphicon-import"></span>
                <?= Helper::urlBuilder('/product/upload', 'Імпорт');?>
            </p>
        </div>
        <?php
    }
}
?>
