<style>
    div #order {
        background-color: white;
        width: 97%;
        border: 10px solid green;
        padding: 20px;
        margin: 20px;
    }
</style>


<?php
echo isset($this->registry['successMessage']) ? : "";
$productId = (int)Helper::getParamFromUrl('product_id');
// Метод виведення повідомлення про додавання товару до кошика
$product = $this->getModel('Product')->getItem($productId);
//print_r($product);exit;
?>

<?php if($product) : ?>
    <div class="product">
        <table style="width:100%">
            <tr>
            <td width="40%"><img src="<?= PRODUCT_IMAGE_PATH . $product['product_image'] ?>" alt="<?php echo $product['name'] ?>" width="500" height=""></td>
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
                <form method="POST" >
                    <input type="number" name="quantity" min="1" max="<?= $product['qty']; ?>" value="1"/>
                    <button <?php if ($product['qty'] == 0) echo("disabled"); ?> class="w3-button w3-black">Купити</button>
                    <input type="hidden" name="<?= $product['product_id'] ?>" value="<?= $product['name'] ?>"/>
                </form>
                <?php
                if (!empty($_POST[$product['product_id']])) {
                    $_SESSION['cart'][] = $product;
                    $_SESSION['qty'][] = 1;
                }

                if (Helper::isAdmin() == 1) {
                    echo '<span class="glyphicon glyphicon-pencil"></span>' . " ";
                    echo Helper::simpleLink('/product/edit', 'Редагувати', array('product_id' => $product['product_id'])) . " ";
                    echo '<span class="glyphicon glyphicon-trash"></span>' . " ";
                    echo Helper::simpleLink('/product/delete', 'Видалити', array('product_id' => $product['product_id']));
                }
                ?>
            </td>
            </tr>
        </table>
    </div>
<?php else : ?>
<center><h2> There is no product with such id</h2></center>
<?php endif ?>


