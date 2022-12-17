<style>
.warning{color: MediumVioletRed   ;}
.error {color: #FF0000;}
.container {width: 500px;clear: both;}
.container input {width: 100%;clear: both;}
.button {
	font-size: 25px;
	border: none;
	padding: 15px 32px;
	background-color: MediumVioletRed   ; 
	color: white; 
	
}
</style>

<?php if(Helper::isAdmin()==1 && !isset($_POST['export'])):?>
<center><h2>Експорт</h2>


<h3>Данні про товари будуть експортовані в файл products.xml в
папку public. Для цього натисніть кнопку 'Експортувати'.</h3>
<br><br>

<form method="POST" >
<button class="button" name="export">Експортувати</button>
</form>
</center>
<?php endif; ?>

<?php
	//повідомлення про успіх або невдачу
	if(isset($_POST['export'])){
		echo '<center><h3>'.$this->registry['export_message'].'</h3></center>';
	};
//якщо не адмін
Helper::isNotAdmin("Ви не маєте права експортувати товари!");	
	
?>
