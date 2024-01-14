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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define the upload directory
    $uploadDir = ROOT . "/img/products/";
    // Create the uploads directory if it doesn"t exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Process the uploaded file
    $uploadFile = $uploadDir . basename($_FILES['product_image']['name']);   
            
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
        echo "Product information and photo uploaded successfully!";
        // You can now store the product information and file path in your database
        // or perform any other necessary actions.
    } else {
        echo "Error uploading product photo.";
    }
}

$categoryId = $this->getModel('Category')->getCategoriesIds();
$categoryName = $this->getModel('Category')->getCategoriesNames();
$categories = array_combine($categoryId, $categoryName);
//збереження введень користувача
$form_data = Helper::FormDataInput(array('sku', 'name', 'price', 'qty', 'description', 'product_image'));

//отримання id
$id = Helper::MaxValue('product_id', 'product');

//якщо адмін
if (Helper::isAdmin() == 1) {
    //якщо кнопка додавання ще не натиснута вивести попередження
    if (!isset($_POST['add'])) {
        echo ("<span class='warning'><center><h3>
		Ви збираєтеся створити новий товар. Якщо ви впевнені,
		корректно введіть данні та натисніть кнопку 'Додати'.
		</h3></center></span><br>");
    }

    //якщо кнопка додавання натиснута вивести помилку
    if (isset($_POST['add']) && $this->registry['error'] != "") {
        echo("<span class='warning'><center><h3>" . $this->registry['error'] . "</h3></center></span><br>");
    }

    //якщо кнопка додавання натиснута і данні введено вірно
    if (isset($_POST['add']) && $this->registry['error'] == "") {
        $messageAdd = "Товар з id = $id успішно створено!";
        Helper::redirect("/product/edit?messageAdd=$messageAdd&product_id = $id");
    }
}
//якщо не адмін
Helper::isNotAdmin("Ви не маєте права додавати товари!");
?>

<?php
//якщо адмін, то показувати форму
if (Helper::isAdmin() == 1):
    ?>

    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <div class="container">

            Sku: <input type="text" name="sku" value="<?= ($product["sku"] ?? '') ?>">
            <span class="error"> <?php echo Helper::isEmpty('product')[1]; ?></span><br><br>

            Назва: <input type="text" name="name" value="<?= ($product["name"] ?? '') ?>">
            <span class="error"> <?php echo Helper::isEmpty('product')[2]; ?></span><br><br>

            <label for="category_id">Категорія:</label>
            <select name="category_id[]" multiple="multiple">
                <?php
                // Масив де ключі - id категорій, значення - імена категорій                
                //array_shift($categories);
                // Вивід опцій випадаючого списку Категорія
                foreach ($categories as $categoryId => $categoryName): ?>	
                    <option value="<?= $categoryId; ?>"><?= $categoryName; ?></option>
    <?php endforeach; ?>
            </select>
            </br></br>
            Ціна: <input type="text" name="price" value="<?= ($product["price"] ?? '') ?>">
            <span class="error"><?php
                echo Helper::isEmpty('product')[3];
                echo Helper::isNumeric()[0];
                ?></span><br><br>

            Кількість: <input type="text" name="qty" value="<?= ($product["qty"] ?? '') ?>" >
            <span class="error"> <?php
                echo Helper::isEmpty('product')[4];
                echo Helper::isNumeric()[1];
                ?></span><br><br>

            Опис:<p><textarea rows="5" cols="56" name="description">
    <?= ($product["description"] ?? '') ?></textarea></p>
            <span class="error"> <?php echo Helper::isEmpty('product')[5]; ?></span><br>

            <label for="product_image">Product Photo:</label>
            <input type="file" name="product_image" id="product_image" accept="image/*" required><br>            
            <input class="button" type="submit" name="add" value="Додати">
        </div>
    </form>
<?php endif; ?>



