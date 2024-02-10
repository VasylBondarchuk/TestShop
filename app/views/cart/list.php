<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
    table, th, td {
        border: 0px solid grey;
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

    .btn-delete {
        border: none;
        background: none;
        padding: 0;
    }

    .btn-delete .glyphicon {
        font-size: 20px; /* Adjust the size as needed */
    }


</style>
<?php
$cart = $this->getModel('Cart');

if (isset($_POST['empty'])) {
    $cart->emptyCart();
}

if (isset($_POST['delete_item'])) {
    $cartItemIndex = $_POST['delete_item'];
    $cart->delCartItem($cartItemIndex);
}
?>

<?php if ($cart->isCartEmpty()): ?>
    <p> <?= $cart->getCartTitle() . ' is empty'; ?></p>
<?php else: ?>
    <form method="POST" >
        <input class="w3-button w3-black" name="empty" type="submit" value="Очистити кошик"/>
    </form>

    <center>
        <h1>            
            <?= $cart->getCartTitle(); ?>
        </h1>
    </center>
    <br>
    <center>
        <table style="width:100%">
            <thead>
                <tr>
                    <?php foreach ($cart->getCartColumnLabels() as $cartColumnLabel): ?>                                            
                        <th><?= $cartColumnLabel ?></th>  
                    <?php endforeach; ?>                   
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart->getCartItems() as $cartItemIndex => $product): ?>
                    <tr>                        
                        <td width="20%"><img src="<?= PRODUCT_IMAGE_PATH . $product['product_image'] ?>" alt="<?= $product['name'] ?>" width="100" height=""></td> 
                        <td><?= $product['name'] ?></td>
                        <td><?= $product['sku'] ?></td>
                        <td><?= $product['price']; ?> грн </td>
                        <td><?= $product['qty'] ?> шт.</td>
                        <td><?= $cart->itemTotalAmount($product['product_id'], $product['price']); ?> грн </td>
                        <td>
                            <form method="POST">            
                                <button type="submit" name="delete_item" value="<?= $cartItemIndex; ?>" class="btn-delete">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </form> 
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </center>
    <br>
<?php endif; ?>
