<?php
require_once '/var/www/html/app/views/admin/admin_products_actions.php';

$categoryId = (int) Helper::getQueryParam('category_id');
$product = $this->getModel('Product');
$productCollection = $product->getProductsByCategory($categoryId);

// Check if there are products in the category
if (empty($productCollection)) {
    echo "There are no products in this category";
} else {
    // Calculate min and max prices
    $minPrice = $product->findCollectionMinMaxPropertyValue($productCollection, 'price', 'min');
    $maxPrice = $product->findCollectionMinMaxPropertyValue($productCollection, 'price', 'max');

    // Call getSortParams() to retrieve sorting parameters
    $sortingParams = Helper::getSortParams('sort');
    $inputMinPrice = Helper::getQueryParam('minPrice');
    $inputMaxPrice = Helper::getQueryParam('maxPrice');

    // Sort the collection
    $sortedProducts = $product->sortCollectionByProperties($productCollection, $sortingParams);
    // Filter the collection   
    $products = $product->filterProductsByPrice($sortedProducts, $inputMinPrice, $inputMaxPrice);

    // Update min and max prices based on filtering
    if (!empty($inputMinPrice) || !empty($inputMaxPrice)) {
        $filteredProducts = $product->filterProductsByPrice($productCollection, $inputMinPrice, $inputMaxPrice);
        $minPrice = $product->findCollectionMinMaxPropertyValue($filteredProducts, 'price', 'min');
        $maxPrice = $product->findCollectionMinMaxPropertyValue($filteredProducts, 'price', 'max');
    }
}
require_once FORMS_HANDLER_PATH . DS . 'sorting_filtering_product_form_fields.php';
?>



<!-- Display products -->
<?php foreach ($products as $product): ?>
    <!-- Product HTML -->
    <div class="product">
        <!-- Product details -->
        <table style="width:100%">
            <!-- Product image -->
            <tr>
                <td width="20%">
                    <img src="<?= PRODUCT_IMAGE_PATH . $product->getProductImage() ?>" alt="<?= $product->getName() ?>" width="500" height="">
                </td>
                <!-- Product name and details -->
                <td width="80%">
                    <h1><?= Helper::urlBuilder('/product/show', $product->getName(), ['product_id' => $product->getProductId()]) ?></h1>
                    <p class="sku"> SKU: <?= $product->getSku(); ?></p>
                    <p> Price: <span class="price"><?= $product->getPrice(); ?></span> UAH </p>
                    <p> <?= $product->getQty() != 0 ? "Quantity: {$product->getQty()}" : 'Out of stock' ?></p>
                    <p> Description: <?= htmlspecialchars_decode($product->getDescription()) ?></p>
                    <!-- Add to cart form -->
                    <form method="POST">
                        <input type="number" name="qty" min="1" max="<?= $product->getQty() ?>" value="1"/>
                        <button <?= $product->getQty() == 0 ? 'disabled' : '' ?> class="w3-button w3-black">Buy</button>
                        <input type="hidden" name="<?= $product->getProductId() ?>" value="<?= $product->getName() ?>"/>
                    </form>
    <?php
    $this->addToCart($product);
    include '/var/www/html/app/views/admin/admin_product_actions.php';
    ?>

                </td>
            </tr>
        </table>
    </div>
<?php endforeach; ?>

