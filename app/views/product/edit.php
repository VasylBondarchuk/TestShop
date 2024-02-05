<style>
    .error {
        color: #FF0000;
    }

    .warning {
        color: green;
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
        background-color: green;
        color: white;
        border: 2px solid green;
    }
</style>

<?php
$product = $this->getModel('Product');
$productId = $this->getId('Product');
$productDetails = $product->getProductById($productId);

//якщо адмін, то показувати форму
if ($this->getModel('Customer')->isAdmin()): ?>
    <center><h2>Редагування товару id = <?= $productId ?></h2></center>

    <form method="POST" action="<?php FORMS_HANDLER_PATH . DS . '/add_product_form.php'; ?>" enctype="multipart/form-data">
        <div class="container">
            
            <label for="sku"> Sku: </label>
            <input type="text" name="sku" value="<?= $productDetails['sku'] ?>">
            <span class="error"> <?php Helper::FormIcorrectInputMessage('sku'); ?></span>
            </br></br>
            
            <label for="name"> Product Name: </label>
            <input type="text" name="name" value="<?= $productDetails['name'] ?>">
            <span class="error"> <?php Helper::FormIcorrectInputMessage('name'); ?></span>
            </br></br>
            
            <label for="category_id"> Category:</label>
            <select name="category_id[]" multiple="multiple">
                <?php foreach ($this->getModel('Category')->getCategories() as $categoryId => $categoryName): ?>	
                <option value="<?= $categoryId; ?>"
                    <?= $product->isProductInCategory($productId,$categoryId) ? 'selected' : '';?>><?= $categoryName;?>
                </option>
                <?php endforeach; ?>
            </select>            
            </br></br>
            
            <label for="price">Price:</label>            
            <input type="text" name="price" value="<?= $productDetails["price"] ?>">
            <span class="error"><?php Helper::FormIcorrectInputMessage("price"); ?></span>
            </br></br>
            
            <label for="qty">Qty:</label>
            <input type="text" name="qty" value="<?= $productDetails["qty"] ?>">
            <span class="error"><?php Helper::FormIcorrectInputMessage("qty"); ?></span>
            </br></br>
            
            <label for="qty">Description:</label>            
            <textarea rows="5" cols="55" name="description"><?= $productDetails["description"] ?></textarea>
            <span class="error"><?= Helper::isEmpty('product')[5]; ?></span>
            </br></br>
            
            <img src="<?= PRODUCT_IMAGE_PATH . $productDetails['product_image']; ?>" alt="<?= $productDetails['name'] ?>" width="400" height="">
            </br></br>
            
            <label for="product_image">Product Photo:</label> 
            <input type="file" name="product_image" id="product_image" accept="image/*"><br>   
            <input class="button" name="Edit" type="submit">
        </div>
    </form>
<?php endif; ?>