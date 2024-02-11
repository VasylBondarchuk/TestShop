<?php
$product = $this->getModel('Product');
$productId = $this->getProductId();
$productDetails = $product->getProductById($productId);

require_once FORMS_HANDLER_PATH . DS . 'edit_product_form_fields.php';

//якщо адмін, то показувати форму
if ($this->getModel('Customer')->isAdmin()):
    ?>
    <center><h2>Редагування товару id = <?= $productId ?></h2></center> 
    <form method="POST" action="<?php FORMS_HANDLER_PATH . DS . '/add_product_form.php'; ?>" enctype="multipart/form-data">
        <div class="container">
            <?= FormGenerator::generateFields($editProductFormFields); ?>
            <div style="text-align: center;">
                <img src="<?= PRODUCT_IMAGE_PATH . $productDetails['product_image']; ?>" alt="<?= $productDetails['name'] ?>" width="400" height="">
            </div>
            <label for="product_image">Product Photo:</label>

            <input type="file" name="product_image" id="product_image" accept="image/*"><br>   
            <input class="button" name="Edit" type="submit">
        </div>
    </form>
<?php endif; ?>