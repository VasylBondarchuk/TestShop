<?php
$product = $viewModel->getProduct();
if ($product) {
    require_once 'show_product.php';
    //require_once '/var/www/html/app/views/admin/admin_product_actions.php';
    if (!empty($_POST[$product->getProductId()])) {
        $product->addToCart($product);
    }
}