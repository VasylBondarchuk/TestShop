<?php

namespace app\modules\customer\Controller\Front;

ini_set('display_errors', 1);
error_reporting(E_ALL);

use app\core\Controller;
use app\core\Helper;
use app\modules\customer\Factory\CustomerRegisterViewModelFactory;
use app\modules\customer\Model\Customer;

class Register extends Controller {

    public function action() {
        $this->setTitle("Registration");
        $viewModel = CustomerRegisterViewModelFactory::create();
        $viewContent = $this->renderView('customer', 'register', ['viewModel' => $viewModel]);
        $this->renderLayout('layout', $viewContent);

        // Check if the form is submitted
        if (isset($_POST['addcustomer'])) {                          
            if (empty($viewModel->getRegistrationCustomerErrors())) {
                $model = new Customer();                
                $model->registerCustomer($viewModel->getRegistrationFormData()); 
                $model->loginRegisteredCustomer();                
                Helper::redirect(INDEX_PAGE_PATH);
                $_SESSION['succsess'] = 'You have registered succesfully!';
                exit;
            }
        }
    } 
}
