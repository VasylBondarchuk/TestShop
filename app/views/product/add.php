<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
    .warning{
        color: orange;
    }
    .error {
        color: #FF0000;
    }
    .container {
        width: 500px;
        clear: both;
    }
    .container input {
        width: 100%;
        clear: both;
    }
    .button {
        font-size: 25px;
        border: none;
        padding: 15px 32px;
        background-color: orange;
        color: white;
        border: 2px solid orange;
    }
</style>

<?php
require_once FORMS_HANDLER_PATH . DS . 'add_product_form_fields.php';

$form_data = Helper::getFormData(['sku', 'name', 'price', 'qty', 'description', 'product_image']);

$customerModel = $this->getModel('Customer');
// Check if the user is logged in and is an admin
$isAdmin = 
        $customerModel->isLogedIn() &&  $customerModel
        ->getCustomerById($customerModel->getLoggedInCustomerId())->isAdmin();

if ($isAdmin): ?>    
    <form method="POST" action="<?php FORMS_HANDLER_PATH . DS . '/add_product_form.php'; ?>" enctype="multipart/form-data">
        <div class="container">
            <?= FormGenerator::generateFields($addProductFormFields); ?>                
            <label for="product_image">Product Photo:</label>

            <input type="file" name="product_image" id="product_image" accept="image/*"><br>   
            <input class="button" name="Edit" type="submit">
        </div>
    </form>
<?php endif; ?>