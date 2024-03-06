<?php

/**
 * Class CustomerController
 */
class AdminController extends Controller
{    
// Метод для авториації клієнта
    public function LoginAction()
    {       
        $this->setTitle("Вхід");

        // Повідомлення при порожньому введенні Email
        $this->registry['empty_email'] = "Введіть Email";

        // Повідомлення при порожньому введенні паролю
        $this->registry['empty_password']="Введіть пароль";

        // змінна логування = успіх
        $this->registry['user_login'] = FALSE; 

        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST')
        {
            $email = filter_input(INPUT_POST, 'email');
            $password = md5(filter_input(INPUT_POST, 'password'));

            if($_POST['password'] && $_POST['email'])
            {
                $params =array ('email'=>$email,'password'=> $password);            
                $customer = $this->getModel('customer')->initCollection()
                ->getCollection()->getItemByParam('email',$params['email']);                                  
            }
            
            //якщо є хоча б один користувач з такою ел. адресою
            if(!empty($customer))
            {
                //якщо ел.пошта та пароль співпадають
                if(($email == $customer['email']) && ($password==$customer['password']))
                {                    
                    // змінна логування = успіх
                    $this->registry['user_login']=TRUE;

                    //отримання id
                    $_SESSION['id'] = $customer['customer_id'];
                
                    //отримання повного імені користувача
                    $_SESSION['first_name']=$customer['first_name'];
                    $_SESSION['last_name']=$customer['last_name'];

                    // редирект на сторінку категорій
                    Helper::redirect('/category/show');    
                }              
            }            
        }
        $this->setView();
        $this->renderLayout();
    }
}