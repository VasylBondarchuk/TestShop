<style>
	div #order {
		background-color: white;
		width: 97%;
		border: 10px solid green;
		padding: 20px;
		margin: 20px;
	}
</style>

<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
	<select name='sortfirst'>
		<?php if($_COOKIE['price']=='ASC'):?>
			<option <?php echo filter_input(INPUT_POST, 'sortfirst') === 'price_ASC' ? 'selected' : '';?> value="price_ASC">від дешевших до дорожчих</option>
			<option <?php echo filter_input(INPUT_POST, 'sortfirst') === 'price_DESC' ? 'selected' : '';?> value="price_DESC">від дорожчих до дешевших</option>
		<?php endif; ?>

		<?php if($_COOKIE['price']=='DESC'):?>
			<option <?php echo filter_input(INPUT_POST, 'sortfirst') === 'price_DESC' ? 'selected' : '';?> value="price_DESC">від дорожчих до дешевших</option>
			<option <?php echo filter_input(INPUT_POST, 'sortfirst') === 'price_ASC' ? 'selected' : '';?> value="price_ASC">від дешевших до дорожчих</option>
		<?php endif; ?>	
	</select>

	<select name='sortsecond'>
		<?php if($_COOKIE['qty']=='ASC'):?>
			<option <?php echo filter_input(INPUT_POST, 'sortsecond') === 'qty_ASC' ? 'selected' : '';?>  value="qty_ASC">по зростанню кількості</option>
			<option <?php echo filter_input(INPUT_POST, 'sortsecond') === 'qty_DESC' ? 'selected' : '';?>  value="qty_DESC">по спаданню кількості</option>
		<?php endif; ?>

		<?php if($_COOKIE['qty']=='DESC'):?>
			<option <?php echo filter_input(INPUT_POST, 'sortsecond') === 'qty_DESC' ? 'selected' : '';?>  value="qty_DESC">по спаданню кількості</option>
			<option <?php echo filter_input(INPUT_POST, 'sortsecond') === 'qty_ASC' ? 'selected' : '';?>  value="qty_ASC">по зростанню кількості</option>
		<?php endif; ?>
	</select>

	Ціна від: <input type="text" name="price[]" value="<?php echo Helper::$var['min_price']; ?>">
	Ціна до: <input type="text" name="price[]" value="<?php echo Helper::$var['max_price']; ?>"><br>
	<input class="w3-button w3-black" type="submit" name="sort" value="Застосувати">

</form>

<?php
// Метод виведення повідомлення про додавання товару до кошика
$products=$this->registry['Product'];

Helper::buttonListener($products);
?>

<?php if(Helper::isAdmin()==1):?>

	<div class="product"><p><span class="glyphicon glyphicon-plus"></span>
		<?php {echo Helper::simpleLink('/product/add', 'Додати товар');} ?>
	</p></div>

	<div class="product"><p><span class="glyphicon glyphicon-export"></span>
		<?php {echo Helper::simpleLink('/product/unload', 'Експорт');} ?>
	</p></div>

	<div class="product"><p><span class="glyphicon glyphicon-import"></span>
		<?php {echo Helper::simpleLink('/product/upload', 'Імпорт');} ?>
	</p></div>
<?php endif; ?>

<?php
foreach($products as $product)  :
	?>

	<div class="product"></a>
		<table style="width:100%">
			<tr>
				<td width="20%"><img src="/img/t00009.png" alt="<?php echo $product['name']?>" width="" height=""></td>
				<td width="80%>	

					<p class="sku">Код: <?php echo $product['sku']?></p>
					<h4><?php echo $product['name']?><h4>
						<p> Ціна: <span class="price"><?php echo $product['price']?></span> грн</p>
						<p> <?php
								if($product['qty'] != 0){echo("Кількість:".$product['qty']."");}
								else{echo('Нема в наявності');}
							?>
						</p>		 
						<p> Опис: <?php echo htmlspecialchars_decode($product['description'])?></p>
						<form method="POST" >
							<input type="number" name="quantity<?php echo $product['id']; ?>" min="1" max="<?php echo $product['qty'];?>" value="1"/>
							<button <?php if($product['qty'] == 0) echo("disabled")?> class="w3-button w3-black">Купити</button>
							<input type="hidden" name="<?php echo $product['id']?>" value="<?php echo $product['name']?>"/>
						</form>

						<?php
						if(!empty($_POST[$product['id']])){
							$_SESSION['cart'][]=$product;
							$_SESSION['qty'][]= $_POST["quantity".$product['id']]!=0 ? $_POST["quantity".$product['id']]:1;
						}

						if(Helper::isAdmin()==1){
							echo '<span class="glyphicon glyphicon-pencil"></span>'." ";
							echo Helper::simpleLink('/product/edit', 'Редагувати', array('id'=>$product['id']))." ";
							echo '<span class="glyphicon glyphicon-trash"></span>'." ";				
							echo Helper::simpleLink('/product/delete', 'Видалити', array('id'=>$product['id']));
						}
						?>
					</td>
				</tr>
			</table>
		</div>

	<?php endforeach; ?>


