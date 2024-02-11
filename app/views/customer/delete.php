<?php
$customers = $this->getModel('Customer')->getCustomersDetails();
print_r($customers);

foreach ($customers as $customer) :
    ?>
    <div class="customer">
        <hr>
        <p>id:       <?php echo $customer['customer_id'] ?></p>
        <p>Прізвище: <?php echo $customer['last_name'] ?></p>
        <p>Ім'я:     <?php echo $customer['first_name'] ?></p>
        <p>Телефон:  <?php echo $customer['telephone'] ?></p>
        <p>email:    <?php echo $customer['email'] ?></p>
        <p>Місто:    <?php echo $customer['city'] ?></p>
        <?php
        if (Helper::isAdmin() == 1) {
            echo '<span class="glyphicon glyphicon-pencil"></span>' . " ";
            echo Helper::urlBuilder('/customer/edit', 'Редагувати', array('customer_id' => $customer['customer_id'])) . " ";
            echo '<span class="glyphicon glyphicon-trash"></span>' . " ";
            echo Helper::urlBuilder('/customer/delete', 'Видалити', array('customer_id' => $customer['customer_id']));
        }
        ?>    
    </div>
    <?php endforeach; ?>
