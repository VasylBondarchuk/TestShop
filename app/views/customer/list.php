<?php

// Данні клієнтів
$customers=$this->registry['customers'];

//ім'я колонки id
$id_column_name = $this->getIdColumnName('Customer');

foreach($customers as $customer)  :
?>
    <div class="customer">
	<hr>
        <p>id:       <?php echo $customer['customer_id']?></p>
        <p>Прізвище: <?php echo $customer['last_name']?></p>
        <p>Ім'я:     <?php echo $customer['first_name'] ?></p>
        <p>Телефон:  <?php echo $customer['telephone']?></p>
        <p>email:    <?php echo $customer['email']?></p>
		<p>Місто:    <?php echo $customer['city']?></p>
    <?php		
		if(Helper::isAdmin()==1)
		{
		echo '<span class="glyphicon glyphicon-pencil"></span>'." ";
		echo Helper::urlBuilder('/customer/edit', 'Редагувати', array($id_column_name=>$customer[$id_column_name]))." ";
		echo '<span class="glyphicon glyphicon-trash"></span>'." ";				
		echo Helper::urlBuilder('/customer/delete', 'Видалити', array($id_column_name=>$customer[$id_column_name]));
		}
	?>    
	</div>
<?php endforeach; ?>
