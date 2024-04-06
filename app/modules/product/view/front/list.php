<?php
require_once '/var/www/html/app/views/admin/admin_products_actions.php';

$categoryId = (int) Helper::getQueryParam('category_id');
$product = $this->getModel('Product');

// Number of products per page
$perPage = 5;

// Current page number (default to 1 if not provided)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Fetch paginated product collection
$productCollection = $product->getPaginatedCollection($perPage, $currentPage);

// Total number of products
$totalProducts = count($product->getCollection());

// Calculate total number of pages
$totalPages = ceil($totalProducts / $perPage);

// Instantiate Pagination class with total number of items and items per page
$pagination = new Pagination($totalItems, $itemsPerPage);

// Get the current page number (from user input or default)
$pageNumber = $_GET['page'] ?? 5;

// Get the subset of items for the current page
$offset = $pagination->calculateOffset($pageNumber);
$limit = $pagination->getItemsPerPage();
$subset = $collection->getSubset($offset, $limit);


// Display the items on the current page
foreach ($subset as $item) {
    // Display each item
}

//require_once FORMS_HANDLER_PATH . DS . 'sorting_filtering_product_form_fields.php';
?>

<!-- Display products -->
<?php foreach ($productCollection as $product): ?>
    <!-- Product HTML -->
    <div class="product">
        <!-- Product details -->
        <table style="width:100%">

            <tr>
                <!-- Product image --> 
                <td width="20%">                                   
                    <?=
                    // Generate the anchor tag for the image
                    Helper::urlBuilder('/product/show', '<img src="' .
                            PRODUCT_IMAGE_PATH . $product->getProductImage() .
                            '" alt="' . $product->getName() .
                            '" width="500" height="">',
                            ['product_id' => $product->getProductId()]);
                    ?>
                </td>

                <!-- Product name and details -->
                <td width="80%">
                    <!-- Product name -->
                    <h1><?=
                        Helper::urlBuilder('/product/show',
                                $product->getName(),
                                ['product_id' => $product->getProductId()])
                        ?>
                    </h1>

                    <!-- Product details -->
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
                    if (isset($_POST[$product->getProductId()])) {
                        $this->addToCart($product);
                    }
                    include '/var/www/html/app/views/admin/admin_product_actions.php';
                    ?>
                </td>
            </tr>
        </table>
    </div>
<?php endforeach; ?>

<!-- Pagination controls -->
<div class="pagination">
    <?php if ($currentPage > 1): ?>
        <!-- Button for the first page -->
        <a href="?page=1">First</a>
        <!-- Button for the previous page -->
        <a href="?page=<?= $currentPage - 1 ?>">Previous</a>
    <?php endif; ?>

    <!-- Display page numbers -->
    <span>Pages:</span>
    <?php for ($page = 1; $page <= $totalPages; $page++): ?>
        <?php if ($page == $currentPage): ?>
            <!-- Highlight the current page number -->
            <span><?= $page ?></span>
        <?php else: ?>
            <!-- Link to other pages -->
            <a href="?page=<?= $page ?>"><?= $page ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($currentPage < $totalPages): ?>
        <!-- Button for the next page -->
        <a href="?page=<?= $currentPage + 1 ?>">Next</a>
        <!-- Button for the last page -->
        <a href="?page=<?= $totalPages ?>">Last</a>
    <?php endif; ?>
</div>


