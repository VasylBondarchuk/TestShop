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
if (isset($_POST['Edit']) && isset($this->registry['success'])) {
// Кнопка натиснута і нема помилкової ситуаціїї - виводимо повідмлення про успіх
    echo("<div class='container'><span class='warning'><center><h3>{$this->registry['success']}</h3></center></span></div><br>");
}

if (isset($_POST['Edit']) && isset($this->registry['error'])) {
// Кнопка натиснута і нема помилкової ситуаціїї - виводимо повідмлення про успіх
    echo("<div class='container'><span class='warning'><center><h3>{$this->registry['error']}</h3></center></span></div><br>");
}// Вивід помилки

?>

<?php
$product = $this->getModel('Product');
$category = $this->getModel('Category');
$productId = $this->getId('Product');
$productDetails = $product->getProductById($productId);
$availableCategories = array_combine($category->getCategoriesIds(), $category->getCategoriesNames());

// Define the image upload directory
$uploadDir = PRODUCT_IMAGE_UPLOAD_DIR;
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }    
    // Process the uploaded file
    $uploadFile = $uploadDir . basename($_FILES['product_image']['name']);
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)){
        echo "Product information and photo uploaded successfully!";        
    } else {
        echo "Error uploading product photo.";
    }
}

//якщо адмін, то показувати форму
if (Helper::isAdmin()): ?>
    <center><h2>Редагування товару id = <?= $productId ?></h2></center>

    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <div class="container">
            
            <label for="sku"> Sku: </label>
            <input type="text" name="sku" value="<?= $productDetails['sku'] ?>">
            <span class="error"> <?php Helper::FormIcorrectInputMessage('sku'); ?></span>
            </br></br>
            
            <label for="name"> Назва: </label>
            <input type="text" name="name" value="<?= $productDetails['name'] ?>">
            <span class="error"> <?php Helper::FormIcorrectInputMessage('name'); ?></span>
            </br></br>
            
            <label for="category_id">Категорія:</label>
            <select name="category_id[]" multiple="multiple">
                <?php foreach ($availableCategories as $categoryId => $categoryName): ?>	
                <option value="<?= $categoryId; ?>" <?= $product->isProductInCategory($productId,$categoryId) ? "selected" :"";?>><?= $categoryName;?></option>
                <?php endforeach; ?>
            </select>            
            </br></br>
            
            <label for="price">Ціна:</label>            
            <input type="text" name="price" value="<?= $productDetails["price"] ?>">
            <span class="error"><?php Helper::FormIcorrectInputMessage("price"); ?></span>
            </br></br>
            
            <label for="qty">Кількість:</label>
            <input type="text" name="qty" value="<?= $productDetails["qty"] ?>">
            <span class="error"><?php Helper::FormIcorrectInputMessage("qty"); ?></span>
            </br></br>
            
            <label for="qty">Опис:</label>            
            <textarea rows="5" cols="55" name="description"><?= $productDetails["description"] ?></textarea>
            <span class="error"><?= Helper::isEmpty('product')[5]; ?></span>
            </br></br>
            <img src="<?= PRODUCT_IMAGE_PATH . $productDetails['product_image']; ?>" alt="<?= $productDetails['name'] ?>" width="400" height="">
            <label for="product_image">Product Photo:</label>
            </br></br>
            
            <input type="file" name="product_image" id="product_image" accept="image/*"><br>   
            <input class="button" name="Edit" type="submit">
        </div>
    </form>
<?php endif; ?>