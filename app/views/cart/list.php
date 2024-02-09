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
<?php if (empty($_POST['order']) || (!empty($_POST['order']) && empty($_SESSION['id']))): ?>

    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
        <input class='w3-button w3-black' name='empty' type='submit' value='Очистити кошик'/>
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

<?php
$cart = $this->getModel('Cart');

if (isset($_POST['empty'])) {
    $cart->emptyCart();
}

if (isset($_POST['delete_item'])) {
    $cartItemIndex = $_POST['delete_item'];
    $cart->delCartItem($cartItemIndex); 
}

foreach ($cart->getCartItems() as $cartItemIndex => $product):
    ?>
    <tr>
        <td><?= ($cartItemIndex + 1) ?></td>
        <td><?= $product['name'] ?></td>
        <td><?= $product['sku'] ?></td>
        <td><?= $product['price']; ?>грн</td>
        <td><?= $cart->itemTotalQty($product['product_id']) ?> шт.</td>
        <td><?= $cart->itemTotalAmount($product['product_id'], $product['price']); ?> грн </td>
        <td>
            <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">            
                <button type="submit" name="delete_item" class="btndelete" value="<?php echo $cartItemIndex; ?>">Delete</button>
            </form>
        </td>
    </tr>	 
<?php endforeach; ?>

<tr>
    <td colspan="4" align="right">Підсумок:</td>
    <td align="left"><strong><?= $cart->cartTotalQty() . " шт."; ?></strong></td>
    <td align="left" colspan="3"><strong><?= number_format($cart->cartTotalAmount(), 2); ?></strong></td>
</tr>
</table>
<br>

<?php if ((!empty($_POST['order']) && empty($_SESSION['id']))): ?>
    <center>
        <h3> Оформити замовлення можуть лише зареєстровані відвідувачі</h3>
        <h3>
            <a href = "<?= $_SERVER['SCRIPT_NAME'] . '/customer/login/'; ?>">Авторизуйтеся</a>
            або
            <a href = "<?= $_SERVER['SCRIPT_NAME'] . '/customer/register/'; ?>">зареєструйтеся на сайті.</a>
        </h3>
    </center>
    </br>
<?php endif; ?>

<?php if (!empty($_SESSION['cart'])): ?>
    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
        <input class="w3-button w3-black" name="order" type="submit" value="Оформити замовлення"/>
    </form>
<?php endif; ?>

<?php
/* якщо натиснуто зареєстр. відвідувачем оформити -
  відправити данні в базу та очистити сесію */

if (!empty($_POST['order']) && !empty($_SESSION['id'])) {
    $cart->orderDetails();
    $_SESSION['cart'] = [];
    $_SESSION['qty'] = [];
    $_SESSION['total_qty'] = [];
}

/* вітання у випадку успішного замовлення */

if (!empty($_POST['order']) && !empty($_SESSION['id'])) {
    echo '<center><h3> Замовлення оформлено.<br>
	Дякуємо за покупку!</h3></center>';
}
?>
