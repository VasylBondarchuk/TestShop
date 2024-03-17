<!-- app/modules/product/view/front/index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
</head>
<body>
<!-- Display products -->
<?php 

foreach ($viewModel->getProductsCollection() as $product): ?>
    <!-- Product HTML -->
    <div class="product">
        <!-- Product details -->
        <table style="width:100%">

            <tr>
                <!-- Product image --> 
                <td width="20%">                                   
                    <?=
                    // Generate the anchor tag for the image
                    app\core\Helper::urlBuilder('/product/show', '<img src="' .
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
                        app\core\Helper::urlBuilder('/product/show',
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
                </td>
            </tr>
        </table>
    </div>
<?php endforeach; ?>

</div>
</body>
</html>
