<?php

/**
 * Class CustomerController
 */
class CustomerController extends Controller
{
    public function IndexAction()
    {
        $this->ListAction();
    }

    // метод для перегляду клієнтів
    public function ListAction()
    {        
        $this->setTitle("Клієнти");
        $this->registry['customers'] = $this->getModel('Customer')
            ->initCollection()
            ->getCollection()
            ->select();
        $this->setView();
        $this->renderLayout();
    }

    // Метод для авториації клієнта
    public function LoginAction()
    {
        $this->setTitle("Вхід");
        // Повідомлення при порожньому введенні Email
        $this->registry['empty_email']="Введіть Email";
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
                $params = array ('email'=>$email,'password'=> $password);
                $customer = $this->getModel('customer')->initCollection()
                    ->getCollection()->getItemByParam('email',$params['email']);

            }

            //якщо є хоча б один користувач з такою ел. адресою
            if(!empty($customer))
            {
                //якщо ел.пошта та пароль співпадають
                if(($email==$customer['email']) && ($password==$customer['password']))
                
                {
                    // змінна логування = успіх
                    $this->registry['user_login'] = TRUE;
                    //отримання id
                    $_SESSION['customer_id'] = $customer['customer_id'];
                    //отримання повного імені користувача
                    $_SESSION['first_name'] = $customer['first_name'];
                    $_SESSION['last_name']  = $customer['last_name'];
                    // редирект на сторінку категорій
                    Helper::redirect('/category/list');
                }
            }
        }
        $this->setView();
        $this->renderLayout();
    }

    //метод для регістрації нового клієнта
    public function RegisterAction()
    {
        $model = $this->getModel('Customer');
        $this->setTitle("Додавання клієнта");
        if ($values = $model->getPostValues()) {
            $model->addCustomer();
        }
        $this->setView();
        $this->renderLayout();
    }

    public function LogoutAction()
    {
        $_SESSION = [];
        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 3600, "/");
        }
        session_destroy();
        Helper::redirect('/category/list');
    }

    // МЕТОД РЕДАГУВАННЯ КЛІЄНТА
    public function EditAction()
    {
        //Встановлюємо назву сторінки
        $this->setTitle("Редагування клієнта");
        // Повертає об'єкт класу Product extends Model
        $model = $this->getModel('Customer');
        // Отримуємо масив данних клієнта, що редагується    
        $this->registry['customers'] = $model->getItem($this->getId('Customer'));

        if(isset($_POST['Edit'])){
            // Викликаємо метод класу Model редагування товару
            $model->editItem(($this->getId($this->getModelName())));
        }
        //$model->editItem(($this->getId($this->getModelName())));
        // Отримуємо масив данних товару, що редагується    
        $this->registry['customers'] = $model->getItem($this->getId('Customer'));

        $this->setView();
        $this->renderLayout();

    }

    // МЕТОД ВИДАЛЕННЯ КЛІЄНТА
    public function DeleteAction()
    {
        // Встановлюємо назву сторінки
        $this->setTitle("Видалення клієнта");

        // Повертає обєкт класу Product extends Model
        $model = $this->getModel('Customer');

        // отримуємо масив данних товару, що редагується    
        $this->registry['product'] = $model->getItem($this->getId($this->getModelName()));

        // Якщо отриманий з запиту id існує в БД - видаляємо
        if (in_array($this->getId('Customer'),$model->getColumnArray($this->getIdColumnName($this->getModelName()))))
        {
            // Викликаємо метод класу Model видалення товару
            $model->deleteItem($this->getId($this->getModelName()));
            // Відображаємо вигляд
            $this->setView();
            // Відображаємо шаблон
            $this->renderLayout();
        }

        // Якщо отриманий з запиту id неіснує в БД    
        else
        {
            //відображаємо шаблон
            $this->renderPartialview('layout');
            echo("Нема такого клієнта");
        }
    }
}
