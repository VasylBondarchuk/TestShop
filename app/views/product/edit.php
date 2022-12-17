<style>
.error {color: #FF0000;}
.warning {color: green;}
.container {width: 500px;clear: both;}
.container input {width: 100%;clear: both;}
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

// Данні товару, що редагується
$product = $this->registry['product'];

//отримання повідомлення про створення товару
$messageAdd  = filter_input(INPUT_GET, 'messageAdd');

//якщо кнопка редагування ще не натиснута - вивести повідомлення про створеня товару
if (!isset($_POST['Edit'])){
	echo ("<span class='warning'><center><h3>".$messageAdd."</h3></center></span>");}

if (isset($_POST['Edit'])){
// Кнопка натиснута і нема помилкової ситуаціїї - виводимо повідмлення про успіх
echo("<div class='container'><span class='warning'><center><h3>{$this->registry['success']}</h3></center></span></div><br>");}

// Вивід помилки
echo("<div class='container'><span class='warning'><center><h3>{$this->registry['error']}</h3></center></span></div><br>");
?>

<?php //якщо адмін, то показувати форму
if(Helper::isAdmin()):
?>

<center>
	<h2>
	РЕДАГУВАННЯ ТОВАРУ
	</h2>
</center>

<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<div class="container">

Sku: <input type="text" name="sku" value="<?php echo $product['sku']?>">
<span class="error"> <?php Helper::FormIcorrectInputMessage('sku');?></span><br><br>

Назва: <input type="text" name="name" value="<?php echo $product["name"]?>">
<span class="error"> <?php Helper::FormIcorrectInputMessage("name");?></span><br><br>

<label for="category_id">Категорія:</label>
<select name="category_id">
<?php

// Масив де ключі - id категорій, значення - імена категорій
$categories = $this->registry['categories'];

// Вивід опцій випадаючого списку Категорія
foreach($categories as $categoryId => $categoryName): ?>	
	<option value="<?= $categoryId;?>"
		<?php if($categoryId == $product['category_id'])echo('selected');?>>
		<?= $categoryName;?>
	</option>
<?php endforeach; ?>
</select>

</br></br>

Ціна:
<input type="text" name="price" value="<?= $product["price"]?>">
<span class="error"><?php Helper::FormIcorrectInputMessage("price");?>
</span>
</br></br>
Кількість:
<input type="text" name="qty" value="<?=$product["qty"]?>" >
<span class="error"> <?php Helper::FormIcorrectInputMessage("qty");?>
</span>
</br></br>
Опис:
<p><textarea rows="5" cols="62" name="description">
<?= $product["description"]?></textarea></p>
<span class="error"> <?= Helper::isEmpty('product')[5];?>
</span>
</br></br>
<input class="button" type="submit" name="Edit" value="Редагувати">
</div>
</form>

<?php endif; ?>