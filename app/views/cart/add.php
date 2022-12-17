<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
table, th, td {
  border: 1px solid grey;
  border-collapse: Gainsboro;
  padding: 10px;
}
table th {
  background-color: Gainsboro;
  color: black;
}
h3{
  color: red;
}
</style>
<?php if(empty($_POST['order']) || (!empty($_POST['order']) && empty($_SESSION['id']))):?>

<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<input class="w3-button w3-black" name="empty" type="submit" value="Очистити кошик"/>
</form>
<?php endif; ?>


<center><table style="width:75%"></center>
<th>Номер</th><th>Назва</th>
<th>Код</th> 
<th>Ціна</th>
<th>Кількість</th>
<th>Разом</th>
<th>Видалити</th>

<center>
<h1>
<strong>
<span class="glyphicon glyphicon-shopping-cart"></span>
Ваш кошик
</strong>
</h1>
</center>
<br>

<?php if((!empty($_POST['order']) && empty($_SESSION['id']))):?>
<center>
<h3> Оформити замовлення можуть лише зареєстровані відвідувачі</h3>
<h3>
	<a href = "<?= $_SERVER['SCRIPT_NAME'] . '/customer/login/'; ?>">Авторизуйтеся</a>
	або
	<a href = "<?= $_SERVER['SCRIPT_NAME'] . '/customer/register/';?>">зареєструйтеся на сайті.</a>
</h3>
</center>
</br>
<?php endif; ?>

<?php
Helper::сartStart();

//загальна кількість всіх замовлених товарів
$total_qty = Helper::cartTotalQty();
//загальна сумма всіх замовлених товарів
$total_amount = Helper::cartTotalAmount();
//передача в сесію
$_SESSION['total_qty'] = $total_qty;

foreach($_SESSION['cart'] as $num => $product):
	//загальна кількість конкретного замовленого товару
	$item_total_qty=Helper::itemTotalQty($num,$product['qty']);
	//загальна сумма конкретного замовленого товару
	$item_total_amount=Helper::itemTotalAmount($num,$product['qty'],$product['price']);
?>

<tr>
	<td><?= (++$num) ?></td>
	<td><?= $product['name'] ?></td>
	<td><?= $product['sku']?></td>
	<td><?= $product['price'];?>грн</td>
	<td><?= $item_total_qty?> шт.</td>
    <td><?= $item_total_amount;?> грн</td>
	<td>
	<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
	<input name="<?= $num ?>" type="submit" onClick="history.go(0)" value="Видалити" class="btndelete" />
	</form>
	</td>
</tr>       
		 
<?php endforeach; ?>

<tr>
<td colspan="4" align="right">Підсумок:</td>
<td align="left"><strong><?= $total_qty." шт."; ?></strong></td>
<td align="left" colspan="3"><strong><?= number_format($total_amount, 2); ?></strong></td>
</tr>
</table>
<br>

<?php if(!empty($_SESSION['cart'])):?>
<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<input class="w3-button w3-black" name="order" type="submit" value="Оформити замовлення"/>
</form>
<?php endif; ?>

<?php

/*якщо натиснуто зареєстр. відвідувачем оформити -
 відправити данні в базу та очистити сесію*/
 
if(!empty($_POST['order']) && !empty($_SESSION['id'])){
	Helper::orderDetails();
	$_SESSION['cart']=[];
	$_SESSION['qty']=[];
	$_SESSION['total_qty']=[];
}

/*вітання у випадку успішного замовлення*/

if(!empty($_POST['order']) && !empty($_SESSION['id'])){
	echo '<center><h3> Замовлення оформлено.<br>
	Дякуємо за покупку!</h3></center>';}
?>