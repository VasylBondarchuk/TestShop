<?php

$cart=$this->registry['cart'];

foreach($cart as $item)  :
?>

    <div class="product">
		
        <p class="sku">Код: <?php echo $item['sku']?></p>
        <h4><?php echo $item['name']?><h4>
        <p> Ціна: <span class="price"><?php echo $$item['price']?></span> грн</p>
        <p> Кількість: <?php echo $$item['qty']?></p>
        <p><?php if(!$itemt['qty'] > 0) { echo 'Нема в наявності'; } ?></p>
		<p> Опис: <?php echo htmlspecialchars_decode($item['description'])?></p>
        <input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="В кошик" class="btnAddAction" />
		<!--<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
		<input type="submit" name="<?php echo $product['id']?>" value="В кошик" />
		</form>-->
		<?php if(isset($_POST[$product['id']])){echo "Ви замовили товар ". $product['id'];
		$_SESSION['cart']=[];
		$_SESSION['cart'][]=$product;
		}
		?>
			
			
		</p>
    </div>
<?php endforeach; ?>

