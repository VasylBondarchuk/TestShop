<?php
// Метод виведення повідомлення про додавання товару до кошика
$products = $this->registry['products'];
if(!$products){
    echo "There are no products in this category";
}

$minPrice = $this->registry['products'] ? min(array_column($this->registry['products'], 'price')) : 0;
$maxPrice = $this->registry['products'] ? max(array_column($this->registry['products'], 'price')) : 0;

$customer = $this->getModel('Customer');
$cart = $this->getModel('Cart');
?>

<?php if($products) : ?>
<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
    <select name='sort_by_price'>
        <?php if (isset($_COOKIE['price'])): ?>
            <?php if ($_COOKIE['price'] == 'ASC'): ?>
                <option <?php echo filter_input(INPUT_POST, 'sort_by_price') == 'price_ASC' ? 'selected' : ''; ?>
                    value="price_ASC">від дешевших до дорожчих
                </option>
                <option <?php echo filter_input(INPUT_POST, 'sort_by_price') == 'price_DESC' ? 'selected' : ''; ?>
                    value="price_DESC">від дорожчих до дешевших
                </option>
            <?php endif; ?>
            <?php if ($_COOKIE['price'] == 'DESC'): ?>
                <option <?php echo filter_input(INPUT_POST, 'sort_by_price') == 'price_DESC' ? 'selected' : ''; ?>
                    value="price_DESC">від дорожчих до дешевших</option>
                <option <?php echo filter_input(INPUT_POST, 'sort_by_price') == 'price_ASC' ? 'selected' : ''; ?>
                    value="price_ASC">від дешевших до дорожчих</option>
                <?php endif; ?>
            <?php else: ?>
            <option <?php echo filter_input(INPUT_POST, 'sort_by_price') == 'price_ASC' ? 'selected' : ''; ?>
                value="price_ASC">від дешевших до дорожчих
            </option>
            <option <?php echo filter_input(INPUT_POST, 'sort_by_price') == 'price_DESC' ? 'selected' : ''; ?>
                value="price_DESC">від дорожчих до дешевших
            </option>
        <?php endif; ?>
    </select>

    <select name='sort_by_qty'>
        <?php if (isset($_COOKIE['qty'])): ?>
            <?php if ($_COOKIE['qty'] == 'ASC'): ?>
                <option <?= filter_input(INPUT_POST, 'sort_by_qty') === 'qty_ASC' ? 'selected' : ''; ?>
                    value="qty_ASC">по зростанню кількості</option>
                <option <?= filter_input(INPUT_POST, 'sort_by_qty') === 'qty_DESC' ? 'selected' : ''; ?>
                    value="qty_DESC">по спаданню кількості</option>
                <?php endif; ?>
                <?php if ($_COOKIE['qty'] == 'DESC'): ?>
                <option <?= filter_input(INPUT_POST, 'sort_by_qty') === 'qty_DESC' ? 'selected' : ''; ?>
                    value="qty_DESC">по спаданню кількості</option>
                <option <?= filter_input(INPUT_POST, 'sort_by_qty') === 'qty_ASC' ? 'selected' : ''; ?>
                    value="qty_ASC">по зростанню кількості</option>
                <?php endif; ?>
            <?php else: ?>
            <option <?= filter_input(INPUT_POST, 'sort_by_qty') === 'qty_ASC' ? 'selected' : ''; ?>
                value="qty_ASC">по зростанню кількості
            </option>
            <option <?= filter_input(INPUT_POST, 'sort_by_qty') === 'qty_DESC' ? 'selected' : ''; ?>
                value="qty_DESC">по спаданню кількості
            </option>
        <?php endif; ?>
    </select>   

    Ціна від: <input type="text" name="minPrice" value="<?= $minPrice; ?>">
    Ціна до: <input type="text" name="maxPrice" value="<?= $maxPrice; ?>">    
    <input class="w3-button w3-black" type="submit" name="sort" value="Застосувати">

</form>
<?php endif; ?>

<?php if ($customer->isAdmin()) : ?>

    <div class="product">
        <p>
            <span class="glyphicon glyphicon-plus"></span>
            <?= Helper::simpleLink('/product/add', 'Додати товар');?>
        </p>
    </div>

    <div class="product">
        <p>
            <span class="glyphicon glyphicon-export"></span>
            <?= Helper::simpleLink('/product/unload', 'Експорт');?>
        </p>
    </div>

    <div class="product">
        <p>
            <span class="glyphicon glyphicon-import"></span>
            <?= Helper::simpleLink('/product/upload', 'Імпорт');?>
        </p>
    </div>
<?php endif; ?>


<?php foreach ($products as $product): ?>
    <div class="product">
        <table style="width:100%">
            <tr>
            <td width="20%"><img src="<?= PRODUCT_IMAGE_PATH . $product['product_image'] ?>" alt="<?= $product['name'] ?>" width="300" height=""></td>            
            <center><h1> <?= Helper::simpleLink('/product/show', $product['name'], array('product_id' => $product['product_id']))?></h1></center>
            <td width="80%>                    
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
                    <input type="number" name="qty" min="1" max="<?= $product['qty']; ?>" value="1"/>
                    <button <?php if ($product['qty'] == 0) echo("disabled"); ?> class="w3-button w3-black">Купити</button>
                    <input type="hidden" name="<?= $product['product_id'] ?>" value="<?= $product['name'] ?>"/>
                    
                </form>
                <?php
                if (!empty($_POST[$product['product_id']])) {
                    $cart->addToCart([
                        'product_id' => $product['product_id'],
                        'sku' => $product['sku'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'qty' => $_POST['qty']]);
                }

                if ($customer->isAdmin()) {
                    echo '<span class="glyphicon glyphicon-pencil"></span>' . " ";
                    echo Helper::simpleLink('/product/edit', 'Редагувати', ['product_id' => $product['product_id']]) . ' ';
                    echo '<span class="glyphicon glyphicon-trash"></span>' . " ";
                    echo Helper::simpleLink('/product/delete', 'Видалити', ['product_id' => $product['product_id']]);
                }
                ?>
            </td>
            </tr>
        </table>
    </div>

<?php endforeach; ?>


