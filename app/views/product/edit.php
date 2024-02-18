<?php
// Get the product ID from the query parameter
$productId = (int) Helper::getQueryParam('product_id');

// Get the product details from the model
$productModel = $this->getModel('Product');
$product = $productModel->getProductById($productId);

if ($product) :
    // Include necessary form files
    require_once FORMS_HANDLER_PATH . DS . 'edit_product_form_fields.php';
    require_once FORMS_HANDLER_PATH . DS . 'add_product_form.php'; 
?>

    <?php if ($this->getModel('Customer')->isAdmin()) : ?>
        <!-- Display the form only if the user is an admin -->
        <center><h2>Редагування товару <?= $product->getName() ?></h2></center> 
        <form method="POST" action="<?= FORMS_HANDLER_PATH . DS . 'add_product_form.php' ?>" enctype="multipart/form-data">
            <div class="container">
                <?= FormGenerator::generateFields($editProductFormFields); ?>
                <div style="text-align: center;">
                    <img src="<?= PRODUCT_IMAGE_PATH . $product->getProductImage(); ?>" alt="<?= $product->getName(); ?>" width="400" height="">
                </div>
                <label for="product_image">Product Photo:</label>
                <input type="file" name="product_image" id="product_image" accept="image/*"><br>   
                <input class="button" name="Edit" type="submit">
            </div>
        </form>
    <?php endif; ?>

<?php else : ?>
    <!-- Display a message if no product is found with the given ID -->
    <center><h2> There is no product with such id</h2></center>
<?php endif ?>
