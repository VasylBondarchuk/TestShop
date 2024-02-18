<?php

$skuFieldDetails = [
    'label' => 'Sku:',
    'type' => 'text',
    'name' => 'sku', 
    'value' => '',
    'error' => Helper::emptyFieldMessage('sku')
];
$nameFieldDetails = [
    'label' => 'Name:',
    'type' => 'text',
    'name' => 'name',
    'value' => '',
    'error' => Helper::emptyFieldMessage('name')
];
$categoryIdFieldDetails = [
    'label' => 'Category:',
    'type' => 'select',
    'name' => 'category_id[]',
    'options' => $this->getModel('Category')->getCategories(),
    'value' => '',
    'error' => Helper::emptyFieldMessage('name'),
    'multiple' => true 
];
$priceFieldDetails = [
    'label' => 'Price:',
    'type' => 'text',
    'name' => 'price', // Make sure "name" key is defined
    'value' => '',
    'error' => Helper::emptyFieldMessage('price')
];
$qtyFieldDetails = [
    'label' => 'Qty:',
    'type' => 'text',
    'name' => 'qty', // Make sure "name" key is defined
    'value' => '',
    'error' => Helper::emptyFieldMessage('qty')
];
$descriptionFieldDetails = [
    'label' => 'Description:',
    'type' => 'textarea',
    'name' => 'description',
    'value' => '',
    'error' => Helper::emptyFieldMessage('description')
];

$addProductFormFields = [
    $skuFieldDetails,
    $nameFieldDetails,
    $categoryIdFieldDetails,
    $priceFieldDetails,
    $qtyFieldDetails,
    $descriptionFieldDetails  
    ];

