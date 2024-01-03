<style>
.warning{color: LightSeaGreen ;}
.error {color: #FF0000;}
.container {width: 50%;clear: both;}
.container input {width: 100%;clear: both;}
.button {
	font-size: 25px;
	border: none;
	padding: 15px 32px;
	background-color: LightSeaGreen ; 
	color: white; 
	border: 2px solid LightSeaGreen ;
}
</style>

<center><h2>Реєстрація на сайті</h2></center>

<?php

error_reporting(E_ALL);

//якщо кнопка редагування ще не натиснута
if (!isset($_POST['addcustomer'])){
	echo ("<span class='warning'><center><h3>
	Ви збираєтеся зареєструватися. Якщо ви впевнені,
	корректно введіть данні та натисніть кнопку 'Зареєструватися'.
	</h3></center></span><br>");}

//якщо кнопка реєстрації натиснута і данні введено вірно
if (isset($_POST['addcustomer']) && Helper::$var['message']==1){
	echo ("<span class='warning'><center><h3>
	Ви упішно зареєструвалися!</h3></center></span><br>");
}
//якщо кнопка реєстрації натиснута і данні введено невірно
elseif(isset($_POST['addcustomer']) && Helper::$var['message']==0){
	echo ("<span class='warning'><center><h3>
Введений вами email належить вже зареєстрованому користувачу.<br>
Введіть інший.</h3></center></span><br>");
}
?>

<?php

//збереження введень користувача
$form_data=Helper::FormDataInput(array('last_name','first_name','telephone',
'email','password','pass_confirm','city'));

/*якщо кнопка реєстрації ще натиснута або натиснута,
 але данні введено невірно*/

if(!isset($_POST['addcustomer']) || (isset($_POST['addcustomer'])
	&& (Helper::$var['message'] == 0 ))) : ?>

<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<div class="container">

Прізвище: <input type="text" name="last_name"
value="<?php echo ($form_data[0]);?>">
<span class="error">
<?php
	echo Helper::isEmpty('customer')[1];
	echo Helper::isUkrInput()[0];
?>
</span>

<br><br>

Ім'я: <input type="text" name="first_name"
value="<?php echo $form_data[1];?>">
<span class="error">
<?php
 	echo Helper::isEmpty('customer')[2];
	echo Helper::isUkrInput()[1];?>
</span>
<br><br>

Номер телефона: <input type="text" name="telephone"
value="<?php echo $form_data[2];?>">
<span class="error">
<?php
 	echo Helper::isEmpty('customer')[3];
	echo Helper::isCorrectPhoneInput()[0];?>
</span>

<br><br>

email: <input type="text" name="email"
value="<?php echo $form_data[3];?>">
<span class="error">
<?php
 	echo Helper::isEmpty('customer')[4];
	echo Helper::isCorrectEmailInput()[0];
?>
</span>

<br><br>

Пароль: <input type="password" name="password"
value="<?php echo $form_data[4];?>">
<span class="error">
<?php
 echo Helper::isEmpty('customer')[5];
 echo Helper::isCorrectPasswordInput()[0];
?>
</span>

<br><br>

Підтвердіть пароль: <input type="password" name="pass_confirm"
value="<?php echo $form_data[5];?>">
<span class="error">
<?php
	echo Helper::isSeparateEmpty('pass_confirm')[0];
	echo Helper::isCorrectPasswordInput()[1];
	echo Helper::isConfirmedInput('password','pass_confirm');
?>
</span>

<br><br>

Місто: <input type="text" name="city" value="<?php echo $form_data[6];?>">
<span class="error">
<?php
	echo Helper::isEmpty('customer')[6];
	echo Helper::isUkrInput()[2];
?>
</span>
	
<br><br>

<input class="button" type="submit" name="addcustomer" value="Зареєструватися">
</div>
</form>
<?php endif; ?>
