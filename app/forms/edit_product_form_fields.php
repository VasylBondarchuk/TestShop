<?php

$skuFieldDetails = [
    'label' => 'Sku:',
    'type' => 'text',
    'name' => 'sku', 
    'value' => $productDetails['sku'],
    'error' => Helper::emptyFieldMessage('sku')
];
$nameFieldDetails = [
    'label' => 'Name:',
    'type' => 'text',
    'name' => 'name',
    'value' => $productDetails['name'],
    'error' => Helper::emptyFieldMessage('name')
];
$categoryIdFieldDetails = [
    'label' => 'Category:',
    'type' => 'select',
    'name' => 'category_id[]',
    'options' => $this->getModel('Category')->getCategories(),
    'value' => $product->getProductCategories($productId),
    'error' => Helper::emptyFieldMessage('name'),
    'multiple' => true 
];
$priceFieldDetails = [
    'label' => 'Price:',
    'type' => 'text',
    'name' => 'price', // Make sure "name" key is defined
    'value' => $productDetails['price'],
    'error' => Helper::emptyFieldMessage('price')
];
$qtyFieldDetails = [
    'label' => 'Qty:',
    'type' => 'text',
    'name' => 'qty', // Make sure "name" key is defined
    'value' => $productDetails['qty'],
    'error' => Helper::emptyFieldMessage('qty')
];
$descriptionFieldDetails = [
    'label' => 'Description:',
    'type' => 'textarea',
    'name' => 'description',
    'value' => $productDetails['description'],
    'error' => Helper::isEmpty('product')
];

$editProductFormFields = [
    $skuFieldDetails,
    $nameFieldDetails,
    $categoryIdFieldDetails,
    $priceFieldDetails,
    $qtyFieldDetails,
    $descriptionFieldDetails  
    ];

