<style>
    .warning {
        color: #FF0000;
    }
    .container {
        width: 800px;
        clear: both;
    }
    .container input {
        width: 100%;
        clear: both;
    }
    .button {
        font-size: 25px;
        border: none;
        padding: 15px 32px;
        background-color: red;
        color: white;
        border: 2px solid red;
    }
</style>

<?php
$productId = (int)Helper::getParamFromUrl('product_id');
$product = $this->getModel('Product')->getItem($productId);
?>

<?php if ($this->getModel('Customer')->isAdmin()): ?>
    <center>
        <h2>
            Видалення товару з id = <?= $this->getId('Product');?>
        </h2>
    </center>

    <span class='warning'>
        <center>
            <h3>
                Ви збираєтеся видалити товар з id = <?= $this->getId('Product'); ?> <br>
                "Якщо ви впевнені, натисніть кнопку 'Видалити'
            </h3>
        </center>
    </span>

    <?php if($product) : ?>
    <div class="product">
        <table style="width:100%">
            <tr>
            <td width="40%"><img src="<?= PRODUCT_IMAGE_PATH . $product['product_image'] ?>" alt="<?php echo $product['name'] ?>" width="300" height=""></td>
            <center><h1> <?= $product['name']; ?></h1></center>
            <td width="60%>                    
                <p class="sku"> Код: <?= $product['sku'] ?></p>                    
                <p> Ціна: <span class="price"><?= $product['price'] ?></span> грн</p>
                <p> <?php
                    if ($product['qty'] != 0) {
                        echo("Кількість:" . $product['qty'] . "");
                    } else {
                        echo('Нема в наявності');
                    }
                    ?>
                </p>
                <p> Опис: <?= htmlspecialchars_decode($product['description']) ?></p>                            
            </td>
            </tr>
        </table>
    </div>

    <?php
    //здійснити редірект на сторінку продуктів
    if (isset($_POST['Delete'])) {
        $product->deleteProduct($this->getId('Product'));
        Helper::redirect("/category/list");        
    }
    ?>    


    <?php
//якщо адмін, кнопка ще не натиснута
    if (!isset($_POST['Delete'])):
        ?>

        <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
            <div class="container">
                <input class="button" type="submit" name="Delete" value="Видалити" >
            </div>
        </form>
    <?php endif; ?>
<?php endif; ?>

<?php
$productId = (int)Helper::getParamFromUrl('product_id');
$product = $this->getModel('Product')->getItem($productId);
?>


<?php else : ?>
<center><h2> There is no product with such id</h2></center>
<?php endif ?>
