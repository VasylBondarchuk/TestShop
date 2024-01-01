<style>
.error {color: #FF0000;}
.warning {color: DodgerBlue;}
.container {width: 500px;clear: both;}
.container input {width: 100%;clear: both;}
.button {
	font-size: 25px;
	border: none;
	padding: 15px 32px;
	background-color: DodgerBlue; 
	color: white; 
	border: 2px solid DodgerBlue;
}
</style>

<center><h2>Авторизація на сайті</h2></center>

<?php
//якщо кнопка увійти ще не натиснута
if (!isset($_POST['login'])){
	echo ("<span class='warning'><center><h3>
	Ви збираєтеся авторизуватися на сайті. Корректно введіть данні та натисніть
	кнопку 'Увійти'.
	</h3></center></span><br>");}
	
//якщо кнопка увійти натиснута	і данні вірні
if (isset($_POST['login']) && Model::$user_loged!=""){
echo ("<span class='warning'><center><h3>Ласкаво просимо, ".$_SESSION['first_name']."</h3></center></span><br>");}
elseif(isset($_POST['login']) && Model::$user_loged=="") {echo ("<span class='warning'><center><h3>Невірний email або(та) пароль!</h3></center></span><br>");}
//змінні помилок
$emailErr = $passErr="";

//виведення попереджень
if(isset($_POST['email'])){if (empty($_POST['email'])) {$emailErr = "Введіть email!";}}
if(isset($_POST['password'])){if (empty($_POST['password'])) {$passErr = "Введіть пароль!";}}
?>

<?php if(!isset($_POST['login']) || ((isset($_POST['login'])) &&
Model::$user_loged == "")) : ?>

<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<div class="container">
email: <input type="text" name="email"><span class="error"> <?php echo $emailErr;?></span>
<br><br>
Пароль: <input type="text" name="password"><span class="error"> <?php echo $passErr;?></span>
<br><br>
<input class="button" type="submit" name="login" value="Увійти">
</div>
</form>

<?php endif; ?>



