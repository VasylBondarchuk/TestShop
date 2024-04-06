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
<?php //якщо адмін, то показувати форму
$customers=$this->registry['customers'];
if(Helper::isAdmin()==1):
?>

<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<div class="container">

Прізвище: <input type="text" name="last_name" value="<?php echo $customers['last_name']?>">
<span class="error"><?php Helper::FormIcorrectInputMessage("last_name");?></span><br><br>

Ім'я: <input type="text" name="first_name" value="<?php echo $customers['first_name']?>">
<span class="error"> <?php Helper::FormIcorrectInputMessage("first_name");?></span><br><br>

Телефон: <input type="text" name="telephone" value="<?php echo $customers['telephone']?>">
<span class="error"><?php Helper::FormIcorrectInputMessage("telephone");?></span><br><br>

email: <input type="text" name="email" value="<?php echo $customers['email']?>" >
<span class="error"> <?php Helper::FormIcorrectInputMessage("email");?></span><br><br>

Місто: <input type="text" name="city" value="<?php echo $customers['city']?>" >
<span class="error"> <?php Helper::FormIcorrectInputMessage("city");?></span><br><br>

<input class="button" type="submit" name="Edit" value="Edit">
</div>
</form>


<?php endif; ?>
