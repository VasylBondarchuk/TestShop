<?php

class Helper {

    //метод перевірки правильності введення паролю та підтвердження
    public static function isCorrectPassword($password) {
        if (isset($_POST[$password])) {
            if ((strlen($_POST[$password]) >= 8) &&
                    preg_match("#[0-9]+#", $_POST[$password]) &&
                    preg_match("#[a-zA-Z]+#", $_POST[$password])) {
                return TRUE;
            }
        }
        return FALSE;
    }

    //метод перевірки правильності підтвердження паролю
    public static function isConfirmOk($password, $confirm) {
        return (self::CleanInput($_POST[$password]) ==
                self::CleanInput($_POST[$confirm]));
    }

    //метод перевірки нецифрових введеннь
    public static function isNumericInput(array $params) {
        foreach ($params as $element) {
            if (isset($element) && (!is_numeric($element) || ($element < 0))) {
                return FALSE;
            }
        }
        return TRUE;
    }

    //метод перевірки нецифрових введеннь
    public static function Numeric($input) {
        if (isset($_POST[$input]) && !empty($_POST[$input]) && (!is_numeric($_POST[$input]) || ($_POST[$input] < 0))) {
            return FALSE;
        }
        return TRUE;
    }

    public static function validateCustomerInput($name1, $name2, $telephone, $email, $password, $confirm, $city) {
        if (!self::isCorrectPhone($telephone) ||
                !self::isCorrectEmail($email) || !self::isCorrectPassword($password) || !self::isUkrainian($city) ||
                !$this->confirmPassword($password, $confirm)) {
            return false;
        }

        return true;
    }

    private static function confirmPassword($password, $confirm) {
        return $password === $confirm;
    }

    public static function urlBuilder($url, $linkText, $params = []): string {
        // Ensure $url starts with a slash
        $url = '/' . ltrim($url, '/');

        // Append query parameters, if any
        if (!empty($params)) {
            $query = http_build_query($params);
            $url .= '?' . $query;
        }

        // Construct the anchor tag
        return '<a href="' . route::getBP() . $url . '">' . htmlspecialchars($linkText) . '</a>';
    }

    public static function redirect(string $path): void {
        // Validate path
        if (!is_string($path) || empty($path)) {
            throw new InvalidArgumentException("Invalid redirect path");
        }

        // Construct full URL
        $server_host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $url = $server_host . route::getBP() . $path;

        // Perform redirect
        header("Location: $url");
        exit(); // Ensure no further code execution
    }

    //вивід помилок при введенні паролів
    public static function isCorrectPasswordInput() {
        //масив помилок
        $params = array('password' => '', 'pass_confirm' => '');
        foreach ($params as $column => &$error) {
            if (!empty($_POST[$column])) {
                if (strlen($_POST[$column]) < 8) {
                    $error = "Пароль має містити мінімум 8 символів!";
                } elseif (!preg_match("#[0-9]+#", $_POST[$column])) {
                    $error = "Пароль має містити хочаб одну цифру!";
                } elseif (!preg_match("#[a-zA-Z]+#", $_POST[$column])) {
                    $error = "Пароль має містити лише англійські літери(мінімум одну)!";
                }
            }
        }
        return array_values($params);
    }

    public static function sanitizeInput(string|array|null $inputData): mixed {
    // Check if input data is null
    if ($inputData === null) {
        return null;
    }

    // Initialize the variable to hold sanitized data
    $sanitizedInput = '';

    // If the input is an array, sanitize each element recursively
    if (is_array($inputData)) {
        $sanitizedInput = [];
        foreach ($inputData as $key => $value) {
            $sanitizedInput[$key] = self::sanitizeInput($value);
        }
    } else {
        // Trim whitespace from the beginning and end of the input
        $trimmedInputData = trim($inputData);
        // Remove backslashes
        $stripSlashesData = stripslashes($trimmedInputData);
        // Convert special characters to HTML entities to prevent XSS attacks
        $sanitizedInput = htmlspecialchars($stripSlashesData);
    }

    return $sanitizedInput;
}


    /**
     * Get and sanitize a value from $_POST.
     *
     * @param string $field The field name.
     * @return mixed The sanitized value from $_POST or null if not found.
     */
    public static function getPostValue(string $field) {
        $rawValue = filter_input(INPUT_POST, $field, FILTER_SANITIZE_SPECIAL_CHARS);
        // Sanitize the raw value using the sanitizeInput method
        return self::sanitizeInput($rawValue);
    }

    public static function isEmpty(string $field): bool {
        return empty(self::getPostValue($field));
    }

    public static function emptyFieldMessage(string $fieldName): string {
        return "The field '{$fieldName}' is required.";
    }

    /**
     * Get parameter value from the URL query string and sanitize it.
     *
     * @param string $paramName The name of the parameter to retrieve.
     * @return mixed|null The sanitized value of the parameter if found, or null if not found.
     */
    public static function getQueryParam(string $paramName) {
        // Get the value of the parameter from the URL query string
        $paramValue = filter_input(INPUT_GET, $paramName);

        // Sanitize the parameter value
        $sanitizedValue = self::sanitizeInput($paramValue);

        // Return the sanitized parameter value if found, otherwise return null
        return $sanitizedValue !== false ? $sanitizedValue : null;
    }

    public static function displayErrorMessage($input, $errorType) {
        switch ($errorType) {
            case 'empty':
                echo "Field {$input} is required";
                break;
            case 'numeric':
                echo "Field {$input} must be a number";
                break;
            case 'phone_format':
                echo "Phone number must contain only digits";
                break;
            case 'email_format':
                echo "Please enter a valid email address";
                break;
            default:
                echo "";
        }
    }

    public static function validateInput($input, $value) {
        switch ($input) {
            case 'sku':
            case 'name':
            case 'last_name':
            case 'first_name':
            case 'city':
                if (empty($value)) {
                    self::displayErrorMessage($input, 'empty');
                    return false;
                }
                break;
            case 'price':
            case 'qty':
                if (empty($value) || !is_numeric($value)) {
                    self::displayErrorMessage($input, 'numeric');
                    return false;
                }
                break;
            case 'last_name':
            case 'first_name':
            case 'city':
                if (!self::isUkrainian($value)) {
                    self::displayErrorMessage($input, 'ukrainian');
                    return false;
                }
                break;
            case 'telephone':
                if (!preg_match('/^\d+$/', $value)) {
                    self::displayErrorMessage($input, 'phone_format');
                    return false;
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    self::displayErrorMessage($input, 'email_format');
                    return false;
                }
                break;
            default:
                return true;
        }
        return true;
    }

    // Method to handle file uploads
    public static function handleFileUpload(): array {
        $filteredData = [];

        if (isset($_FILES['product_image'])) {
            $fileName = $_FILES['product_image']['name'];
            // Sanitize the file name
            $sanitizedFileName = self::sanitizeInput($fileName);
            // Store the sanitized file name
            $filteredData['product_image'] = $sanitizedFileName;
        }
        return $filteredData;
    }

// Method to retrieve form data
    public static function getFormData(array $columns): array {
        $filteredData = [];

        foreach ($_POST as $key => $value) {
            // Check if the form field corresponds to a database column
            if (in_array($key, $columns)) {
                // Sanitize input data before storing it
                $filteredData[$key] = self::sanitizeInput($value);
            }
        }
        // Handle file uploads separately
        $filteredData += self::handleFileUpload();

        return $filteredData;
    }
    
public static function getSortParams(string $field): array {
    // Initialize sorting parameters array
    $sortParams = [];

    // Retrieve sorting parameter from POST data
    $sortParam = filter_input(INPUT_GET, $field);    
    // Check if sorting parameter is set
    if ($sortParam) {
        // Split the sorting parameter into direction and property
        $sortParts = explode('_', $sortParam);

        // Extract sorting direction and property
        $sortOrder = $sortParts[0]; // "asc" or "desc"
        $sortField = $sortParts[1]; // "price" or "qty"

        // Add sorting parameter to the array
        $sortParams[$sortField] = $sortOrder;
    }

    return $sortParams;
}
}

