<?php

/**
 * Class Customer
 */
class Customer extends Model
{
    function __construct()
    {
        $this->table_name = "customer";
        $this->id_column = "customer_id";
    }

    public function getName()
    {
        return 'customer';
    }
	
	// МЕТОД ДОДАВАННЯ (РЕЄСТРАЦІЇ) НОВОГО КЛІЄНТА
    public function addCustomer()
	{
		//імена колонок таблиці
		$columns = $this->getColumnsNames();

		//id нового користувача
		$customer_id = $this->MaxValue($this->id_column) + 1;

		//параметри форми
		$params=array_merge([$customer_id],Helper::FormData($columns));

		//отримання шифрованого паролю md5
		$password ='';

		if(isset($_POST['password']))
		{
			if(!empty($_POST['password'])){$password = md5(Helper::CleanInput($_POST['password']));}
		}

		//перевірка корректності введень

		$correctInput = 0;

		//непорожні корректні введення
		if ($this->isEmpty(Helper::FormData($columns)) &&
			Helper::CorrectCustomerInput($columns[1],$columns[2],$columns[3],
			$columns[4],$columns[5],'pass_confirm',$columns[6])){$correctInput = 1;}
		
		//якщо введений email унікальний для бази	
		if(empty($this->getItemByParam('email',$params[4])))
		{
			//якщо корректно введені всі поля - редагувати
			if (isset($_POST['addcustomer']) && $correctInput==1)
			{
				//додаємо до БД							
				$this->addItem($columns,array($customer_id,$params[1],
				$params[2],$params[3],$params[4],$password,$params[6],0));
				
				//змінна успішного реєстрування 
				Helper::$var['message']=1;
			}
		}
		//змінна неуспішного реєстрування із-за дублікату ел. пошти
		else{
			Helper::$var['message']= 0;
		}	
		return $this;
	}
}