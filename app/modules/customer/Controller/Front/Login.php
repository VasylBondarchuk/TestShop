<?php

namespace app\modules\customer\Controller\Front;

ini_set('display_errors', 1);
error_reporting(E_ALL);

use app\core\Controller;
use app\core\Helper;
use app\modules\customer\Factory\CustomerLoginViewModelFactory;
use app\modules\customer\Factory\CustomerRepositoryFactory;

class Login extends Controller {

    public function action() {
        $this->setTitle("Log in");
        $viewModel = CustomerLoginViewModelFactory::create();

        // Check if form is submitted
        if (isset($_POST['login'])) {
            $viewContent = $this->renderView('customer', 'login', ['viewModel' => $viewModel]);
            $this->renderLayout('layout', $viewContent);
            
            $emailInput = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); 
            $passwordInput = filter_input(INPUT_POST, 'password');
            $customerRepository = CustomerRepositoryFactory::create();            
            $customer = $customerRepository->getByEmail($emailInput);            
            
            // Check if customer exists and password is verified
            if ($customer && password_verify($passwordInput, $customer->getPassword())) {
                $customer->loginCustomer();
                // Redirect to categories page
                Helper::redirect('/category/index');
                exit;
            }

            /*if (empty($viewModel->getCustomerLoginErrors())) {
                $customer->loginCustomer();
                // Redirect to a success page or login page
                Helper::redirect(INDEX_PAGE_PATH);
                exit;
            }*/
        } else {
            $viewContent = $this->renderView('customer', 'login', []);
            $this->renderLayout('layout', $viewContent);
        }
    }
}
