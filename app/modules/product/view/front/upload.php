<style>
.warning{color: LightSeaGreen ;}
.error {color: #FF0000;}
.container {width: 500px;clear: both;}
.container input {width: 100%;clear: both;}
.button {
	font-size: 25px;
	border: none;
	padding: 15px 32px;
	background-color: DarkOrange  ; 
	color: white; 
	
}
</style>
<?php if(Helper::isAdmin()==1 && !isset($_POST['import'])):?>


<center><h2>Імпорт</h2>

<h3>Для імпорту данних помістіть файл import.xml в
папку public і натисніть кнопку 'Імпортувати'.</h3>
<br><br>

<form method="POST" >
<button class="button" name="import">Імпортувати</button>
</form>
</center>
<?php endif; ?>

<?php
	//якщо не адмін
	Helper::isNotAdmin("Ви не маєте права імпортувати товари!");
	if(isset($_POST['import'])){echo '<center><h3>'.$this->registry['import_message'].'</h3></center>';};
?>



