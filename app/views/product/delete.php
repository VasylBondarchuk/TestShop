<style>
.warning {color: #FF0000;}
.container {width: 500px;clear: both;}
.container input {width: 100%;clear: both;}
.button {
	font-size: 25px;
	border: none;
	padding: 15px 32px;
	background-color: red; 
	color: white; 
	border: 2px solid red;
}
</style>

<?php if(Helper::isAdmin()==1): ?>
<center><h2>
Видалення товару з id = <?php $id = Helper::getId(); echo $id;?>
</h2></center>
<?php endif; ?>

<?php
if(Helper::isAdmin()==1)
{
	//виведення попередження
	echo "<span class='warning'>
            <center>
            <h3>
	Ви збираєтеся видалити товар з id = $id.".'<br>'.
	"Якщо ви впевнені, натисніть кнопку 'Видалити'
	</h3></center></span>";
}
?>

<?php $product=$this->registry['product'];if(Helper::isAdmin()==1): ?>
    <div class="product"></a>
	    <p class="sku">Код: <?php echo $product['sku']?></p>
        <h4><?php echo $product['name']?><h4>
        <p> Ціна: <span class="price"><?php echo $product['price']?></span> грн</p>
        <p> Кількість: <?php echo $product['qty']?></p>
        <p><?php if(!$product['qty'] > 0) { echo 'Нема в наявності'; } ?></p>
		<p> Опис: <?php echo htmlspecialchars_decode($product['description'])?></p> 
	</div>
<?php endif; ?>	

<?php
if(Helper::isAdmin()==1){
	//здійснити редірект на сторінку продуктів
	if (isset($_POST['Delete'])){Helper::redirect("/product/list");}
}
//якщо не адмін
Helper::isNotAdmin("Ви не маєте права видаляти товари!");
?>

<?php
//якщо адмін, кнопка ще не натиснута
if(!isset($_POST['Delete']) && Helper::isAdmin()):?>

<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<div class="container">
<input class="button" type="submit" name="Delete" value="Видалити" >
</div>
</form>

<?php endif; ?>

