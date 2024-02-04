<?php

/**
 * Class Customer
 */
class Customer extends Model
{
    const LOGIN_PATH = '/customer/login';
    const LOGOUT_PATH = '/customer/logout';
    const REGISTER_PATH = '/customer/register';
    
    function __construct()
    {
        $this->table_name = "customer";
        $this->id_column = "customer_id";
    }    
	
    // МЕТОД ДОДАВАННЯ (РЕЄСТРАЦІЇ) НОВОГО КЛІЄНТА
    public function addCustomer()
	{
		//імена колонок таблиці
		$columns = $this->getColumnsNames();
		//параметри форми
		$rawParams = Helper::FormData($columns);
                $params = $rawParams;
                array_pop($params);
                //print_r($params);exit;
		//отримання шифрованого паролю md5
		$password = '';
		if(isset($_POST['password']))
		{
                    if(!empty($_POST['password'])){
                            $password = md5(Helper::CleanInput($_POST['password']));
                            
                    }
		}
		//перевірка корректності введень
		$correctInput = 0;

		//непорожні корректні введення
		if ($this->isEmpty(Helper::FormData($columns)) &&
			Helper::CorrectCustomerInput(
                                $columns[1],
                                $columns[2],
                                $columns[3],
                                $columns[4],
                                $columns[5],
                                'pass_confirm',
                                $columns[6]
                                )
                        )
                    {
                    $correctInput = 1; 
                }
		
		//якщо введений email унікальний для бази	
		if(empty($this->getItemByParam('email', $params[4])))
		{
			//якщо корректно введені всі поля - редагувати
			if (isset($_POST['addcustomer']) && $correctInput == 1)
			{
                           $columns2 = $columns;
                            array_shift($columns2);
                            print_r($columns2); 
                            print_r(array($params[1], $params[2],$params[3],$params[4],$password, $params[6], 0));
                            $this->addItem($columns2, array($params[1], $params[2],$params[3],$params[4],$password, $params[6], 0));
			
				//змінна успішного реєстрування 
				Helper::$var['message'] = 1;
			}
		}
		//змінна неуспішного реєстрування із-за дублікату ел. пошти
		else{
			Helper::$var['message']= 0;
		}	
		return $this;
	}
        
        // Масив id категорій
	public function getCustomersDetails() : array
	{
            $customers = $this->initCollection()->getCollection()->select();
            return $customers;
	}        
        
	public function getLogedInCustomerId() 
	{            
            return (int)isset($_SESSION['customer_id'])
                    ?  $_SESSION['customer_id']
                    : '';
	}
        
        // Масив id категорій
	public function getCustomerById(int $customerId) : array
	{
            return $this->getItem($customerId);           
	}
        
        // Масив id категорій
	public function getCustomerAdminRole(int $customerId) : int 
	{
            return $this->getItem($customerId)['admin_role'];           
	}
        
        // Масив id категорій
	public function isLogedIn() : bool
	{
            return isset($_SESSION['customer_id']);           
	}        
        
        // Масив id категорій
	public function getLoginPath() : string
	{
           return route::getBP() . self::LOGIN_PATH;          
	}
        
        // Масив id категорій
	public function getLogoutPath() : string
	{
           return route::getBP() . self::LOGOUT_PATH;          
	}
        
        // Масив id категорій
	public function getRegisterPath() : string
	{
           return route::getBP() . self::REGISTER_PATH;          
	}
        
        // Масив id категорій
	public function getCustomerFullName(int $customerId) : string
	{
           return $this->getCustomerById($customerId)['first_name'] . ' '
                   . $this->getCustomerById($customerId)['last_name'];          
	}
}
