<?php
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {    
    $uploadDir = PRODUCT_IMAGE_UPLOAD_DIR;      
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }   
    $uploadFile = $uploadDir . basename($_FILES['product_image']['name']);
    if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
        $error =  "Виникла проблема з завантаженням зображення.";
    }
}
// Get the product ID from the query parameter
$productId = (int) Helper::getQueryParam('product_id');

// Get the product details from the model
$productModel = $this->getModel('Product');

$product = $productModel->getProductById($productId);

if ($product) {
    // Include necessary form files
    require_once FORMS_HANDLER_PATH . DS . 'edit_product_form_fields.php';    

    $customerModel = $this->getModel('Customer');

    // Check if the user is logged in and is an admin
    $isAdmin = $customerModel->isLogedIn()
            && $customerModel->getCustomerById($customerModel->getLoggedInCustomerId())->isAdmin();   
    
    if ($isAdmin) {
        ?>
        <!-- Display the form only if the user is an admin -->
        <center><h2>Редагування товару <?= $product->getName() ?></h2></center>
        <form method="POST" enctype="multipart/form-data">
            <div class="container">
                <?= FormGenerator::generateFields($editProductFormFields); ?>
                <div style="text-align: center;">
                    <img src="<?= PRODUCT_IMAGE_PATH . $product->getProductImage(); ?>" alt="<?= $product->getName(); ?>" width="400" height="">
                </div>
                <label for="product_image">Product Photo:</label>
                <input type="file" name="product_image" id="product_image" accept="image/*"><br>
                <input type="hidden" name="product_id" value="<?= $productId ?>">
                <input type="hidden" name="error" value="<?= $error;?>">
                <input class="button" name="Edit" type="submit">
            </div>
        </form>
    <?php } else { ?>
        <!-- Display a message if the user is not authorized -->
        <center><h2>Error: Access denied. You are not authorized to view this page.</h2></center>
    <?php }
} else { ?>
    <!-- Display a message if no product is found with the given ID -->
    <center><h2> There is no product with such id</h2></center>
<?php } ?>