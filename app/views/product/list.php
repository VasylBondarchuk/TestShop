<?php
// Get products from the registry
$products = $this->registry['products'] ?? [];

// Check if there are products in the category
if (empty($products)) {
    echo "There are no products in this category";
} else {
    // Calculate min and max prices
    $minPrice = min(array_column($products, 'price'));
    $maxPrice = max(array_column($products, 'price'));

    // Check if the customer is an admin
    $isAdmin = $this->getModel('Customer')->isAdmin();
    ?>

    <!-- Sorting and filtering form -->
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <!-- Sort by price -->
        <select name="sort_by_price">
            <option value="price_ASC">From cheapest to most expensive</option>
            <option value="price_DESC">From most expensive to cheapest</option>
        </select>

        <!-- Sort by quantity -->
        <select name="sort_by_qty">
            <option value="qty_ASC">By increasing quantity</option>
            <option value="qty_DESC">By decreasing quantity</option>
        </select>

        <!-- Price range -->
        <input type="text" name="minPrice" value="<?= $minPrice; ?>"> to
        <input type="text" name="maxPrice" value="<?= $maxPrice; ?>">

        <!-- Apply button -->
        <input class="w3-button w3-black" type="submit" name="sort" value="Apply">
    </form>

    <!-- Display products -->
    <?php foreach ($products as $product): ?>
        <!-- Product HTML -->
        <div class="product">
            <!-- Product details -->
            <table style="width:100%">
                <!-- Product image -->
                <tr>
                    <td width="20%">
                        <img src="<?= PRODUCT_IMAGE_PATH . $product['product_image'] ?>" alt="<?= $product['name'] ?>" width="300" height="">
                    </td>
                    <!-- Product name and details -->
                    <td width="80%">
                        <h1><?= Helper::urlBuilder('/product/show', $product['name'], ['product_id' => $product['product_id']]) ?></h1>
                        <p class="sku"> Code: <?= $product['sku'] ?></p>
                        <p> Price: <span class="price"><?= $product['price'] ?></span> UAH</p>
                        <p> <?= $product['qty'] != 0 ? "Quantity: {$product['qty']}" : 'Out of stock' ?></p>
                        <p> Description: <?= htmlspecialchars_decode($product['description']) ?></p>
                        <!-- Add to cart form -->
                        <form method="POST">
                            <input type="number" name="qty" min="1" max="<?= $product['qty'] ?>" value="1"/>
                            <button <?= $product['qty'] == 0 ? 'disabled' : '' ?> class="w3-button w3-black">Buy</button>
                            <input type="hidden" name="<?= $product['product_id'] ?>" value="<?= $product['name'] ?>"/>
                        </form>

                        <?php
                        if (!empty($_POST[$product['product_id']])) {
                            $cartManager= $this->getModel('CartManager');
                            $cartItem = $this->getModel(
                                    'CartItem',
                                    $product['product_id'],
                                    $product['sku'],
                                    $product['name'],
                                    $product['price'],
                                    $_POST['qty'],
                                    $product['product_image']
                            );                            
                        }
                        ?>

                        <!--Admin actions-->
        <?php if ($isAdmin):
            ?>
                            <span class="glyphicon glyphicon-pencil"></span>
                            <?= Helper::urlBuilder('/product/edit', 'Edit', ['product_id' => $product['product_id']]) ?>
                            <span class="glyphicon glyphicon-trash"></span>
                            <?= Helper::urlBuilder('/product/delete', 'Delete', ['product_id' => $product['product_id']]) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endforeach; ?>

<?php } ?>
