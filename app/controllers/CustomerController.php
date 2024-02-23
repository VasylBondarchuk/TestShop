<?php

/**
 * Class CustomerController
 */
class CustomerController extends Controller {

    public function IndexAction() {
        $this->ListAction();
    }

    // метод для перегляду клієнтів
    public function ListAction() {
        $this->setTitle("Клієнти");
        $this->setView();
        $this->renderLayout();
    }

public function LoginAction() {
    $this->setTitle("Вхід");
    //print_r($_POST['login']);exit;    
    if(isset($_POST['login'])){
        //print_r($_POST['login']);exit;
        $email = filter_input(INPUT_POST, 'email');
        $password = md5(filter_input(INPUT_POST, 'password'));        
        // Check if email and password are provided
        if (!$email || !$password) {            
            $this->setView();
            $this->renderLayout();
            return;
        }        
        $customerModel = $this->getModel('customer');
        $customer = $customerModel->getCustomerByEmail($email);
        
        // Check if customer exists
        if ($customer) {
            // Verify password
            if($password === $customer->getPassword()) {                           
                // Set session variables
                $_SESSION['customer_id'] = $customer->getCustomerId();
                $_SESSION['first_name'] = $customer->getFirstName();
                $_SESSION['last_name'] = $customer->getLastName();                
                // Redirect to categories page
                Helper::redirect('/category/list');
                return; 
            }
        }
    }    
    // Render the login form if login was unsuccessful
    $this->setView();
    $this->renderLayout();
}

    //метод для регістрації нового клієнта
    public function RegisterAction() {
        $model = $this->getModel('Customer');
        $this->setTitle("Додавання клієнта");
        $this->setView();
        $this->renderLayout();
        if (isset($_POST)) {
            $model->addCustomer();
        }
        
    }

    public function LogoutAction() {
        $_SESSION = [];
        // expire cookie
        if (!empty($_COOKIE[session_name()])) {
            setcookie(session_name(), "", time() - 3600, "/");
        }
        session_destroy();
        Helper::redirect('/category/list');
    }

    // МЕТОД РЕДАГУВАННЯ КЛІЄНТА
    public function EditAction() {
        //Встановлюємо назву сторінки
        $this->setTitle("Редагування клієнта");
        // Повертає об'єкт класу Product extends Model
        $model = $this->getModel('Customer');
        // Отримуємо масив данних клієнта, що редагується    
        $this->registry['customers'] = $model->getItem($this->getId('Customer'));

        if (isset($_POST['Edit'])) {
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
    public function DeleteAction() {
        // Встановлюємо назву сторінки
        $this->setTitle("Видалення клієнта");

        // Повертає обєкт класу Product extends Model
        $model = $this->getModel('Customer');

        // отримуємо масив данних товару, що редагується    
        $this->registry['product'] = $model->getItem($this->getId($this->getModelName()));

        // Якщо отриманий з запиту id існує в БД - видаляємо
        if (in_array($this->getId('Customer'), $model->getColumnArray($this->getIdColumnName($this->getModelName())))) {
            // Викликаємо метод класу Model видалення товару
            $model->deleteItem($this->getId($this->getModelName()));
            // Відображаємо вигляд
            $this->setView();
            // Відображаємо шаблон
            $this->renderLayout();
        }

        // Якщо отриманий з запиту id неіснує в БД    
        else {
            //відображаємо шаблон
            $this->renderPartialview('layout');
            echo("Нема такого клієнта");
        }
    }
}
