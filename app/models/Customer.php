<?php

/**
 * Class Customer
 */
class Customer extends Model {

    const LOGIN_PATH = '/customer/login';
    const LOGOUT_PATH = '/customer/logout';
    const REGISTER_PATH = '/customer/register';

    private int $customerId;
    private string $lastName;
    private string $firstName;
    private string $telephone;
    private string $email;
    private ?string $password;
    private string $city;
    private int $adminRole;

    function __construct() {
        $this->table_name = "customer";
        $this->id_column = "customer_id";
    }

    // Getters and Setters
    public function setCustomerId(int $customerId): void {
        $this->customerId = $customerId;
    }

    public function getCustomerId(): int {
        return $this->customerId;
    }

    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function setTelephone(string $telephone): void {
        $this->telephone = $telephone;
    }

    public function getTelephone(): string {
        return $this->telephone;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setPassword(?string $password): void {
        $this->password = $password;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setCity(string $city): void {
        $this->city = $city;
    }

    public function getCity(): string {
        return $this->city;
    }

    public function setAdminRole(int $adminRole): void {
        $this->adminRole = $adminRole;
    }

    public function getAdminRole(): int {
        return $this->adminRole;
    }

    /**
     * Retrieve a collection of customers.
     * 
     * @return array Array of Customer objects.
     */
    public function getCollection(): array {
        $db = new DB();
        $customersData = $db->query("SELECT * FROM $this->table_name");

        $customers = [];
        foreach ($customersData as $customerData) {
            $customer = new Customer();
            $customer->setCustomerId($customerData['customer_id']);
            $customer->setLastName($customerData['last_name']);
            $customer->setFirstName($customerData['first_name']);
            $customer->setTelephone($customerData['telephone']);
            $customer->setEmail($customerData['email']);
            $customer->setPassword($customerData['password']);
            $customer->setCity($customerData['city']);
            $customer->setAdminRole($customerData['admin_role']);
            $customers[] = $customer;
        }

        return $customers;
    }   
    

    // МЕТОД ДОДАВАННЯ (РЕЄСТРАЦІЇ) НОВОГО КЛІЄНТА
    public function addCustomer() {
        //імена колонок таблиці
        $columns = $this->getColumnsNames();
        //параметри форми
        $rawParams = Helper::FormData($columns);
        $params = $rawParams;
        array_pop($params);        
        //отримання шифрованого паролю md5
        $password = '';
        if (isset($_POST['password'])) {
            if (!empty($_POST['password'])) {
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
        ) {
            $correctInput = 1;
        }

        //якщо введений email унікальний для бази	
        if (empty($this->getItemByParam('email', $params[4]))) {
            //якщо корректно введені всі поля - редагувати
            if (isset($_POST['addcustomer']) && $correctInput == 1) {
                $columns2 = $columns;
                array_shift($columns2);                
                $this->addItem($columns2, array($params[1], $params[2], $params[3], $params[4], $password, $params[6], 0));

                //змінна успішного реєстрування 
                Helper::$var['message'] = 1;
            }
        }
        //змінна неуспішного реєстрування із-за дублікату ел. пошти
        else {
            Helper::$var['message'] = 0;
        }
        return $this;
    }

    // Масив id категорій
    public function getCustomersDetails(): array {
        $customers = $this->initCollection()->getCollection()->select();
        return $customers;
    }

    public function getLogedInCustomerId() {
        return (int) isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : '';
    }

    // Масив id категорій
    public function getCustomerById(int $customerId): array {
        return $this->getItem($customerId);
    }

    // Масив id категорій
    public function getCustomerAdminRole(int $customerId): int {
        return $this->getItem($customerId)['admin_role'];
    }

    // Масив id категорій
    public function isLogedIn(): bool {
        return isset($_SESSION['customer_id']);
    }

    // Масив id категорій
    public function getLoginPath(): string {
        return route::getBP() . self::LOGIN_PATH;
    }

    // Масив id категорій
    public function getLogoutPath(): string {
        return route::getBP() . self::LOGOUT_PATH;
    }

    // Масив id категорій
    public function getRegisterPath(): string {
        return route::getBP() . self::REGISTER_PATH;
    }

    // Масив id категорій
    public function getCustomerFullName(int $customerId): string {
        return $this->getCustomerById($customerId)['first_name'] . ' '
                . $this->getCustomerById($customerId)['last_name'];
    }

    //перевірка чи відвідувач є адміном
    public function isAdmin(): bool {
        return isset($_SESSION['customer_id'])
        ? $this->getCustomerAdminRole($_SESSION['customer_id'])
        : false;
    }
}
