<?php
// Get the product ID from the query parameter
$productId = (int) Helper::getQueryParam('product_id');
// Get the product details from the model
$product = $this->getModel('Product')->getProductById($productId);

if ($product) {
    if ($this->getModel('Customer')->isAdmin()): ?>
        <center>
            <h2>
                Видалення товару <?= $product->getName(); ?>
            </h2>
        </center>
        <?php
    endif;
    require_once 'show_product.php';
    
    if (!isset($_POST['Delete'])):
        ?>
        <form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
            <div class="container">
                <input class="button" type="submit" name="Delete" value="Delete">
            </div>
        </form>
    <?php
    endif;
} else {
    echo "<center><h2> There is no product with such id</h2></center>";
}
?>
