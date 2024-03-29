<?php
namespace app\models;

use app\core\Model;
use app\core\Route;
/**
 * Class Customer
 */
class Customer extends Model {

    const LOGIN_PATH = '/customer/login';
    const LOGOUT_PATH = '/customer/logout';
    const REGISTER_PATH = '/customer/register';
    const ADMIN_ROLE_ID = 1;

    private int $customerId;
    private string $lastName;
    private string $firstName;
    private string $telephone;
    private string $email;
    private string $password;
    private string $city;
    private int $adminRole = 0; // Initialize adminRole with a default value

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

    /**
     * Register a new customer.
     *
     * @param array $formData The form data submitted during registration.
     * @return array|bool An array of errors if validation fails, or true if the registration was successful.
     */
    public function registerCustomer(array $formData) {
        // Validate the form data
        $validationErrors = FormValidator::validateRegistrationForm($formData);
        if (!empty($validationErrors)) {
            return $validationErrors;
        }

        // Hash the password before saving it to the database
        $hashedPassword = password_hash($formData['password'], PASSWORD_DEFAULT);

        // Set up the data to be added to the database
        $data = [
            'first_name' => $formData['first_name'],
            'last_name' => $formData['last_name'],
            'telephone' => $formData['telephone'],
            'email' => $formData['email'],
            'city' => $formData['city'],
            'password' => $hashedPassword,
        ];

        $columns = $this->getFormFieldsFromDbColumns($data);

        // Save the customer to the database using the addItem method
        return $this->addItem($columns, $data);
    }

    public function getLoggedInCustomerId(): ?int {
        if (isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id'])) {
            return (int) $_SESSION['customer_id'];
        } else {
            return null; // Return null if the customer is not logged in
        }
    }

    public function isLogedIn(): bool {
        return isset($_SESSION['customer_id']);
    }

    public function getLoginPath(): string {
        return Route::getBP() . self::LOGIN_PATH;
    }

    public function getLogoutPath(): string {
        return Route::getBP() . self::LOGOUT_PATH;
    }

    public function getRegisterPath(): string {
        return Route::getBP() . self::REGISTER_PATH;
    }

    public function getCustomerFullName(): string {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function isAdmin(): bool {
        // Check if the customer is logged in and has an admin role
        if ($this->isLogedIn() && $this->getAdminRole() === self::ADMIN_ROLE_ID) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retrieve a customer object by email.
     *
     * @param string $email The email of the customer to retrieve.
     * @return Customer|null The customer object if found, or null if not found.
     */
    public function getCustomerByEmail(string $email): ?Customer {
        // Sanitize the email input
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            return null; // Invalid email format
        }

        // Perform a database query to retrieve the customer by email
        $db = new DB();
        $sql = "SELECT * FROM $this->table_name WHERE email = :email LIMIT 1";
        $parameters = [':email' => $email];
        $customerData = $db->query($sql, $parameters);

        // If customer data is found, create a Customer object and return it
        if (!empty($customerData)) {
            $customerRow = array_shift($customerData);
            $customer = new Customer();
            $customer->setCustomerId($customerRow['customer_id']);
            $customer->setLastName($customerRow['last_name']);
            $customer->setFirstName($customerRow['first_name']);
            $customer->setTelephone($customerRow['telephone']);
            $customer->setEmail($customerRow['email']);
            $customer->setPassword($customerRow['password']);
            $customer->setCity($customerRow['city']);
            $customer->setAdminRole($customerRow['admin_role']);

            return $customer;
        } else {
            return null; // Customer not found
        }
    }

    /**
     * Retrieve a customer object by ID.
     *
     * @param int $customerId The ID of the customer to retrieve.
     * @return Customer|null The customer object if found, or null if not found.
     */
    public function getCustomerById(int $customerId): ?Customer {
        // Perform a database query to retrieve the customer by ID
        $db = new DB();
        $sql = "SELECT * FROM $this->table_name WHERE customer_id = :customer_id LIMIT 1";
        $parameters = [':customer_id' => $customerId];
        $customerData = $db->query($sql, $parameters);

        // If customer data is found, create a Customer object and return it
        if (!empty($customerData)) {
            $customerRow = array_shift($customerData);
            $customer = new Customer();
            $customer->setCustomerId($customerRow['customer_id']);
            $customer->setLastName($customerRow['last_name']);
            $customer->setFirstName($customerRow['first_name']);
            $customer->setTelephone($customerRow['telephone']);
            $customer->setEmail($customerRow['email']);
            $customer->setPassword($customerRow['password']);
            $customer->setCity($customerRow['city']);
            $customer->setAdminRole($customerRow['admin_role']);

            return $customer;
        } else {
            return null; // Customer not found
        }
    }

    public function loginCustomer() {
        $_SESSION['customer_id'] = $this->getCustomerId(); // Assuming you have the ID of the newly registered customer
        $_SESSION['first_name'] = $this->getFirstName();
        $_SESSION['last_name'] = $this->getFirstName();
    }

    public function isEmailUnique(string $email): bool {
        $customer = $this->getCustomerByEmail($email);
        return $customer == null;
    }

    /**
     * Verify if the entered email belongs to a registered customer.
     *
     * @param string $email The email of the customer.
     * @return bool True if the email belongs to a registered customer, false otherwise.
     */
    public function verifyCustomerEmail(string $email): bool {
        $customer = $this->getCustomerByEmail($email);
        return $customer !== null;
    }

    /**
     * Verify if the entered password matches the password of the customer with the given email.
     *
     * @param string $email The email of the customer.
     * @param string $password The password to verify.
     * @return bool True if the password matches, false otherwise.
     */
    public function verifyCustomerPassword(string $email, string $password): bool {
        $customer = $this->getCustomerByEmail($email);
        if ($customer !== null) {
            return password_verify($password, $customer->getPassword());
        }
        return false;
    }

    /**
     * Verify if the entered email and password belong to a registered customer.
     *
     * @param string $email The email of the customer.
     * @param string $password The password to verify.
     * @return array|null If authentication succeeds, returns null. If authentication fails, returns an array containing error messages.
     */
    public function verifyCustomer(string $email, string $password): ?array {
        $errors = $this->getCustomerVerificationErrors($email, $password);
        if (!empty($errors)) {
            return $errors;
        }
        return null;
    }

    /**
     * Get errors from customer verification process.
     *
     * @param string $email The email of the customer.
     * @param string $password The password to verify.
     * @return array Array of error messages, if any.
     */
    public function getLoginCustomerVerificationErrors(string $email, string $password): array {
        $errors = [];

        // Check if email is empty
        if (empty($email)) {
            $errors['email'] = "Email is required";
        } else {
            // Check if email is found
            if (!$this->verifyCustomerEmail($email)) {
                $errors['email'] = "Email not found";
            }
        }

        // Check if password is empty
        if (empty($password)) {
            $errors['password'] = "Password is required";
        } else {
            // Check if password is incorrect
            if (!$this->verifyCustomerPassword($email, $password)) {
                $errors['password'] = "Incorrect password";
            }
        }

        return $errors;
    }

    /**
     * Get errors from customer registration process.
     *
     * @param string $email The email of the customer.
     * @return array Array of error messages, if any.
     */
    public function getRegistrationCustomerVerificationErrors(string $email): string {
        $errors = '';

        // Check if email is empty
        if (FormValidator::isValidEmail($email) && !$this->isEmailUnique($email)) {
            $errors = "This email is already registered";
        }
        return $errors;
    }

    public function getRegistrationCustomerErrors(array $formData) {
        $errors = [];

        // Retrieve form validation errors
        $validationErrors = FormValidator::validateRegistrationForm($formData);
        if (!empty($validationErrors)) {
            $errors = $validationErrors;
        }

        // Retrieve customer verification errors
        $verificationErrors = $this->getRegistrationCustomerVerificationErrors($formData['email']);
        if (!empty($verificationErrors)) {
            $errors['email'] = $verificationErrors;
        }

        return $errors;
    }
}
