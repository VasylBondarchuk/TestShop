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
    <?php		
		if(Helper::isAdmin()==1)
		{
		echo '<span class="glyphicon glyphicon-pencil"></span>'." ";
		echo Helper::simpleLink('/customer/edit', 'Редагувати', array('id'=>$customers['id']))." ";
		echo '<span class="glyphicon glyphicon-trash"></span>'." ";				
		echo Helper::simpleLink('/customer/delete', 'Видалити', array('id'=>$customers['id']));
		}
	?>    
	</div>
<?php endforeach; ?>
