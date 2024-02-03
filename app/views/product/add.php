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
$form_data = Helper::FormDataInput(array('sku', 'name', 'price', 'qty', 'description', 'product_image'));
if (Helper::isAdmin()):
    ?>
    <div class='product'>
        Ви збираєтеся створити новий товар. Якщо ви впевнені,
        корректно введіть данні та натисніть кнопку 'Додати'.        
    </div>
    <br><br>
    <form method="POST" action="<?php FORMS_HANDLER_PATH . DS . 'add_product_form.php'; ?>" enctype="multipart/form-data">
        <div class="container">
            <label for="sku"> Sku:</label>
            <input type="text" name="sku">
            <span class="error"> <?php echo Helper::isEmpty('product')[1]; ?></span>
            <br><br>

            <label for="name"> Назва:</label>
            <input type="text" name="name" >
            <span class="error"> <?php echo Helper::isEmpty('product')[2]; ?></span>
            <br><br>

            <label for="category_id">Категорія:</label>
            <select name="category_id[]" multiple="multiple">
                <?php foreach ($this->getModel('Category')->getCategories() as $categoryId => $categoryName): ?>	
                    <option value="<?= $categoryId; ?>"><?= $categoryName; ?></option>
                <?php endforeach; ?>
            </select>
            </br></br>

            <label for="price"> Price:</label>
            <input type="text" name="price">
            <span class="error"><?php
                echo Helper::isEmpty('product')[3];
                echo Helper::isNumeric()[0];
                ?></span>
            <br><br>

            <label for="qty"> Qty:</label>
            <input type="text" name="qty">
            <span class="error"> <?php
                echo Helper::isEmpty('product')[4];
                echo Helper::isNumeric()[1];
                ?></span>
            <br><br>

            <label for="description"> Description: </label>
            <textarea rows="5" name="description"></textarea>
            <span class="error"> <?php echo Helper::isEmpty('product')[5]; ?></span><br>

            <label for="product_image">Product Photo:</label>
            <input type="file" name="product_image" id="product_image" accept="image/*" required><br>            
            <input class="button" type="submit" name="add" value="Додати товар">
        </div>
    </form>
<?php endif; ?>