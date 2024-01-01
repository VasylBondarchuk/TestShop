<?php

$customers=$this->registry['customers'];

foreach($customers as $customer)  :
?>
    <div class="customer">
	<hr>
        <p>id:       <?php echo $customer['customer_id']?></p>
        <p>Прізвище: <?php echo $customer['last_name']  ?></p>
        <p>Ім'я:     <?php echo $customer['first_name'] ?></p>
        <p>Телефон:  <?php echo $customer['telephone']?></p>
        <p>email:    <?php echo $customer['email']?></p>
		<p>Місто:    <?php echo $customer['city']?></p>
        <?php echo Helper::simpleLink('/customer/edit', 'Редагувати', array('id'=>$customer['customer_id'])); ?>
    <hr>
	</div>
<?php endforeach; ?>
