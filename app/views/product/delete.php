<style>
    .warning {
        color: #FF0000;
    }
    .container {
        width: 500px;
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

<?php if (Helper::isAdmin() == 1): ?>
    <center><h2>
            Видалення товару з id = <?php $id = Helper::getId();
    echo $id; ?>
        </h2></center>

    <span class='warning'>
        <center>
            <h3>
                Ви збираєтеся видалити товар з id = <?= Helper::getId(); ?> <br>
                "Якщо ви впевнені, натисніть кнопку 'Видалити'
            </h3>
        </center>
    </span>

    <?php $product = $this->registry['product']; ?>   
    <div class="product"></a>
    <p class="sku">Код: <?= $product['sku'] ?></p>
    <h4><?= $product['name'] ?></h4>
    <p> Ціна: <span class="price"><?= $product['price'] ?></span> грн </p>
    <p> Кількість: <?= $product['qty'] ?></p>
    <p>
        <?php
        if (!$product['qty'] > 0) {
            echo 'Нема в наявності';
        }
        ?>
    </p>
    <p>
        Опис: <?= htmlspecialchars_decode($product['description']) ?>
    </p> 
    </div>

    <?php
    //здійснити редірект на сторінку продуктів
    if (isset($_POST['Delete'])) {
        Helper::redirect("/product/list");
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
if (Helper::isAdmin() == 0) {
    Helper::isNotAdmin("Ви не маєте права видаляти товари!");
}
?>
