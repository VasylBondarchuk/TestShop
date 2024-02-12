<?php

class Helper {

    // універсальна змінна-масив  
    public static $var = [];

    // метод перевірки правильності укр. введення
    public static function isUkrainian($input): bool {
        if (isset($_POST[$input])) {
            if (!empty($_POST[$input])) {
                if (!preg_match("/^[-а-яіїєґА-ЯІЇЄҐ']+$/iu", $_POST[$input])) {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    //метод перевірки правильності телефону
    public static function isCorrectPhone($input): bool {
        if (isset($_POST[$input])) {
            if (!empty($_POST[$input])) {
                if (!preg_match("/^[0-9]*$/", $_POST[$input])) {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }
   
    
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

    //метод перевірки корректності данних форми регістрації
    public static function CorrectCustomerInput($name1, $name2, $telephone, $email, $password, $confirm, $city) {
        if (self::isUkrainian($name1) && self::isUkrainian($name2) == TRUE && self::isCorrectPhone($telephone) == TRUE && self::isCorrectEmail($email) == TRUE && self::isCorrectPassword($password) == TRUE && self::isCorrectPassword($confirm) == TRUE && self::isConfirmOk($password, $confirm) == TRUE && self::isUkrainian($city) == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
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

    public static function getCustomer() {
        if (!empty($_SESSION['id'])) {
            return self::getModel('customer')->initCollection()
                            ->filter(array('customer_id' => $_SESSION['id']))
                            ->getCollection()
                            ->selectFirst();
        } else {
            return null;
        }
    }

    //отримання макс. значення конкретної колонки конкретної таблиці
    public static function MaxValue($param, $table_name) {
        $db = new DB();
        $sql = "SELECT MAX(" . $param . ")FROM $table_name;";
        $results = $db->query($sql);
        return floatval($results[0]["MAX(" . $param . ")"]);
    }

    // Метод обробки данних форми
    public static function CleanInput($data) {
        //обрізка пробілів з країв 
        $data = trim($data);
        //обрізка зворотніх слешів
        $data = stripslashes($data);
        //перетворення спецсимволів
        $data = htmlspecialchars($data);

        return $data;
    }

    //отримання значень форми
    public static function FormData() {
        $form_data = [];

        foreach ($_POST as $key => $value) {
            if (isset($_POST[$key])) {
                array_push($form_data, self::CleanInput($value));
            }
        }
        //print_r($form_data);
        return $form_data;
    }

    //метод виведення попереджень при нецифрових введеннях
    public static function isNumeric() {
        //масив помилок
        $params = array('price' => '', 'qty' => '');

        foreach ($params as $column => &$error) {
            if (isset($_POST[$column])) {
                if (!empty($_POST[$column])) {
                    if (!is_numeric($_POST[$column]) || $_POST[$column] < 0) {
                        $error = "Некорректне введення!";
                    }
                }
            }
        }
        return array_values($params);
    }

    //метод виведення попереджень при нецифрових введеннях
    public static function isInputNumeric($input) {
        if (isset($_POST[$input]) && !empty($_POST[$column])) {
            if (!is_numeric($_POST[$column]) || $_POST[$column] < 0) {
                echo "Значення має бути маєбути невід'ємним числом";
            }
        }
    }

    //метод виведення попереджень при введеннях укр. мовою
    public static function isUkrInput() {
        //масив помилок
        $params = array('last_name' => '', 'first_name' => '', 'city' => '');

        foreach ($params as $column => &$error) {
            if (isset($_POST[$column])) {
                if (!empty($_POST[$column])) {
                    if (!preg_match("/^[-а-яіїєґА-ЯІЇЄҐ']+$/iu", $_POST[$column])) {
                        $error = "Некорректне введення";
                    }
                }
            }
        }
        return array_values($params);
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

    public static function isConfirmedInput($password, $confirmation) {
        if (isset($_POST[$password]) && isset($_POST[$confirmation])) {
            if (!empty($_POST[$password]) && !empty($_POST[$confirmation])) {
                if ($_POST[$password] !== $_POST[$confirmation]) {
                    return "Пароль і підтверження не співпадають!";
                }
            }
        }
    }    

    /**
     * Method to sanitize form inputs.
     * Handles arrays recursively.
     *
     * @param mixed $inputData The input data to be sanitized.
     * @return mixed Sanitized input data.
     */
    public static function sanitizeInput(string|array $inputData) {
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

    //отримання значень форми
    public static function FormDataInput(array $params): array {
        //массив данних форми; $params - масив назв полів
        $form_data = array_fill(0, count($params), '');

        //ітерація по полям форми
        for ($i = 0; $i < count($params); $i++) {
            if (isset($_POST[$params[$i]])) {
                if (!empty($_POST[$params[$i]])) {
                    $form_data[$i] = Helper::ClearInput($_POST[$params[$i]]);
                }
            }
        }
        return $form_data;
    }

    /**
     * Get and sanitize a value from $_POST.
     *
     * @param string $field The field name.
     * @return mixed The sanitized value from $_POST or null if not found.
     */
    public static function getPostValue(string $field) {
        return filter_input(INPUT_POST, $field, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public static function isEmpty(string $field): bool {
        return empty(self::getPostValue($field));
    }    

    public static function emptyFieldMessage(string $fieldName): string {
        return "The field '{$fieldName}' is required.";
    }
    
    /**
     * Get parameter value from the URL query string.
     *
     * @param string $paramName The name of the parameter to retrieve.
     * @return mixed|null The value of the parameter if found, or null if not found.
     */
    public static function getQueryParam(string $paramName) {
        // Get the value of the parameter from the URL query string
        $paramValue = filter_input(INPUT_GET, $paramName);

        // Return the parameter value if found, otherwise return null
        return $paramValue !== false ? $paramValue : null;
    }    

    // Перевірка непорожності введення, якщо порожнє повертає TRUE 
    public static function Empty($input) {
        return (isset($_POST[$input]) && empty($_POST[$input])) ? TRUE : FALSE;
    }

    // Перевірка непорожності введення, якщо порожнє повертає TRUE 
    public static function getFilteringInput(string $filteringInputName) {
        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the POST input
            return $_POST[$filteringInputName];
        }
    }

    // Перевірка непорожності введення, якщо порожнє повертає TRUE 
    public static function FormIcorrectInputMessage($input) {
        switch ($input) {
            case "sku":
                if (self::Empty($input)) {
                    echo "Введіть данні";
                };
                break;

            case "name":
                if (self::Empty($input)) {
                    echo "Введіть данні";
                };
                break;

            case "price":
                if (self::Empty($input)) {
                    echo "Введіть данні";
                };
                if (!self::Numeric($input)) {
                    echo "Введіть невід'ємне число";
                }
                break;

            case "qty":
                if (self::Empty($input)) {
                    echo "Введіть данні";
                };
                if (!self::Numeric($input)) {
                    echo "Введіть невід'ємне число";
                }
                break;

            case "last_name":
                if (self::Empty($input)) {
                    echo "Введіть данні";
                };
                if (!self::isUkrainian($input)) {
                    echo "Прізвище має бути введено українською мовою";
                }
                break;

            case "first_name":
                if (self::Empty($input)) {
                    echo "Введіть данні";
                };
                if (!self::isUkrainian($input)) {
                    echo "Ім'я має бути введено українською мовою";
                }
                break;

            case "telephone":
                if (self::Empty($input)) {
                    echo "Введіть данні";
                };
                if (!self::isCorrectPhone($input)) {
                    echo "Телефон має містити лише цифри";
                }
                break;

            case "email":
                if (self::Empty($input)) {
                    echo "Введіть данні";
                };
                if (!self::isCorrectEmail($input)) {
                    echo "Введіть корректний email";
                }
                break;

            case "email":
                if (self::Empty($input)) {
                    echo "Введіть данні";
                };
                if (!self::isCorrectEmail($input)) {
                    echo "Введіть корректний email";
                }
                break;

            case "city":
                if (self::Empty($input)) {
                    echo "Введіть данні";
                };
                if (!self::isUkrainian($input)) {
                    echo "Ім'я має бути введено українською мовою";
                }
                break;

            default:
                echo "";
                return TRUE;
        }
    }

    // Method to handle file uploads
    public static function handleFileUpload(): array {
        $filteredData = [];

        if (isset($_FILES['product_image'])) {
            $fileName = $_FILES['product_image']['name'];
            // Store file name or handle file storage here
            $filteredData['product_image'] = $fileName;
        }
        return $filteredData;
    }

    // Method to retrieve form data
    public static function getFormData(array $columns): array {
        $filteredData = [];

        foreach ($_POST as $key => $value) {
            // Check if the form field corresponds to a database column
            if (in_array($key, $columns)) {
                // Handle file uploads separately
                if ($key === 'product_image') {
                    $filteredData += self::handleFileUpload();
                } else {
                    // For other fields, store form data directly
                    $filteredData[$key] = $value;
                }
            }
        }

        return $filteredData;
    }
}
