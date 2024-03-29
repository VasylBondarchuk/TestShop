<?php

$skuFieldDetails = [
    'label' => 'Sku:',
    'type' => 'text',
    'name' => 'sku', 
    'value' => $product->getSku(),
    'error' => Helper::emptyFieldMessage('sku')
];
$nameFieldDetails = [
    'label' => 'Name:',
    'type' => 'text',
    'name' => 'name',
    'value' => $product->getName(),
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
    'value' => $product->getPrice(),
    'error' => Helper::emptyFieldMessage('price')
];
$qtyFieldDetails = [
    'label' => 'Qty:',
    'type' => 'text',
    'name' => 'qty', // Make sure "name" key is defined
    'value' => $product->getQty(),
    'error' => Helper::emptyFieldMessage('qty')
];
$descriptionFieldDetails = [
    'label' => 'Description:',
    'type' => 'textarea',
    'name' => 'description',
    'value' => $product->getDescription(),
    'error' => Helper::emptyFieldMessage('description')
];

$editProductFormFields = [
    $skuFieldDetails,
    $nameFieldDetails,
    $categoryIdFieldDetails,
    $priceFieldDetails,
    $qtyFieldDetails,
    $descriptionFieldDetails  
    ];

