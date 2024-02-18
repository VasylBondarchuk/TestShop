<?php

$productId = (int) Helper::getQueryParam('product_id');
$product = $this->getModel('Product')->getProductById($productId);
if ($product) {    
    require_once 'show_product.php';
    require_once '/var/www/html/app/views/admin/admin_product_actions.php';
    $this->addToCart($product);   
}