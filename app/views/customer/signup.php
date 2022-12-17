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

//змінні помилок
$emailErr = $passErr="";

//виведення попереджень
if(isset($_POST['email'])){if (empty($_POST['sku'])) {$emailErr = "Введіть email!";}}
if(isset($_POST['password'])){if (empty($_POST['name'])) {$passErr = "Введіть пароль!";}}
?>

<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<div class="container">
email: <input type="text" name="email"><span class="error"> <?php echo $emailErr;?></span>
<br><br>
Пароль: <input type="text" name="password"><span class="error"> <?php echo $passErr;?></span>
<br><br>
<input class="button" type="submit" name="login" value="Увійти">
</div>
</form>




