<?php

// Define the sorting options
$sortingOptions = [
    'asc_price' => 'Price Ascending',
    'desc_price' => 'Price Descending',
    'asc_qty' => 'Qty Ascending',
    'desc_qty' => 'Qty Descending'
];

// Define the selected option (if any)
$selectedOption = Helper::getQueryParam('sort') ?? null;

// Prepare the field details array
$sortDetails = [
    'label' => 'Sort By:',
    'name' => 'sort',
    'type' => 'select',
    'options' => $sortingOptions,
    'value' => $selectedOption,
    'multiple' => false, // Set to true if the select allows multiple selections
    'error' => '' // Optional error message
];

// Define the details for the minimum price input field
$minPriceFieldDetails = [
    'label' => 'From',
    'name' => 'minPrice',
    'type' => 'text',
    'value' => $minPrice, // You can assign the value from your PHP variable
    'error' => '', // If you have any error messages, you can assign them here
];

// Define the details for the maximum price input field
$maxPriceFieldDetails = [
    'label' => 'to',
    'name' => 'maxPrice',
    'type' => 'text',
    'value' => $maxPrice, // You can assign the value from your PHP variable
    'error' => '', // If you have any error messages, you can assign them here
];
?>

<form method="GET" action="<?= $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="category_id" value="<?= $categoryId; ?>">
    <?= FormGenerator::generateField($sortDetails); ?>    
    <?= FormGenerator::generateField($minPriceFieldDetails); ?>
    <?= FormGenerator::generateField($maxPriceFieldDetails); ?>     
    <!-- Apply button -->
    <input class="w3-button w3-black" type="submit" value="Apply">
</form>

