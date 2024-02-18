<?php

$customerModel = $this->getModel('Customer');
$customers = $customerModel->getCollection();

foreach ($customers as $customer) :  ?>
    <div class="customer">
        <hr>
        <p> id:       <?= $customer->getCustomerId(); ?></p>
        <p> Last Name: <?= $customer->getLastName(); ?></p>
        <p> First Name:     <?= $customer->getFirstName(); ?></p>
        <p> Telephone:  <?= $customer->getTelephone(); ?></p>
        <p> email:    <?= $customer->getEmail(); ?></p>
        <p> City:    <?= $customer->getCity(); ?></p>
        
        <?php
        include '/var/www/html/app/views/admin/admin_customers_actions.php';
        ?>
        
    </div>
<?php endforeach; ?>
