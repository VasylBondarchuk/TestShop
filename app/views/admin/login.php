<style>
	
.error {color: #FF0000;}
.warning {color: DodgerBlue;}
.cont {width: 300px;clear: both;text-align:left;}
.cont input {width: 100%;clear: both;}
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

//якщо при непорожніх введеннях авторизація невдала
if(isset($_POST['login']) && Helper::NotEmptyEnter())
{
	echo("<span class='warning'><center><h3>Невірний email або(та) пароль!</h3></center></span><br>");
}
?>

<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<center>	
<div class="cont">
email:
<input type="text" name="email" value="<?php echo $_POST['email'];?>"><span class="error">
<?php if($_POST['login'] && !$_POST['email'])echo $this->registry['empty_email'];?></span><br><br>
Пароль:
<input type="password" name="password" value="<?php echo $_POST['password'];?>">
<span class="error">
<?php if($_POST['login']&&!$_POST['password'])echo $this->registry['empty_password'];?></span>
<br><br>
<input class="button" type="submit" name="login" value="Увійти">

</div>
</center>
</form>
