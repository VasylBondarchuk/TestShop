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

<center><h2> Реєстрація на сайті </h2></center>

<?php
// Include necessary form files
 require_once FORMS_HANDLER_PATH . DS . 'customer_register_fields.php';

?>


<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<div class="container">
<?= FormGenerator::generateFields($customerRegisterFormFields); ?>
<input class="button" type="submit" name="addcustomer" value="Зареєструватися">
</div>
</form>
