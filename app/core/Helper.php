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

    //метод перевірки правильності email
    public static function isCorrectEmail($input) {
        if (isset($_POST[$input])) {
            if (!empty($_POST[$input])) {
                if (!filter_var($_POST[$input], FILTER_VALIDATE_EMAIL)) {
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

    public static function simpleLink($path, $name, $params = []): string {
        if (!empty($params)) {
            $firts_key = array_keys($params)[0];
            foreach ($params as $key => $value) {
                $path .= ($key === $firts_key ? '?' : '&');
                $path .= "$key=$value";
            }
        }
        return '<a href="' . route::getBP() . $path . '">' . $name . '</a>';
    }

    // Метод перенаправлення
    public static function redirect($path) {
        $server_host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $url = $server_host . route::getBP() . $path;
        ob_start();
        header("Location: $url");
        ob_end_flush();
        exit();
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

    //метод виведення попереджень при введені телефону
    public static function isCorrectPhoneInput() {
        //масив помилок
        $params = array('telephone' => '');

        foreach ($params as $column => &$error) {
            if (isset($_POST[$column]) && !empty($_POST[$column]) &&
                    !preg_match("/^[0-9]*$/", $_POST['telephone'])) {
                $error = "Некорректне введення. Введення має містити лише цифри";
            }
        }
        return array_values($params);
    }

    //вивід помилок при введенні email
    public static function isCorrectEmailInput() {
        //масив помилок
        $params = array('email' => '');
        foreach ($params as $column => &$error) {
            if (!empty($_POST[$column])) {
                if (!filter_var($_POST[$column], FILTER_VALIDATE_EMAIL)) {
                    $error = "Некорректне введення";
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

    //метод отримання назв колонок
    public static function getColumnsNames($table_name) {
        $columns = [];
        $db = new DB();
        $sql = "show columns from  $table_name;";
        $results = $db->query($sql);
        foreach ($results as $result) {
            array_push($columns, $result['Field']);
        }
        return $columns;
    }

    //метод обробки данних форми
    public static function ClearInput($data) {
        //обрізка пробілів з країв 
        //$data = trim($data);
        //обрізка зворотніх слешів
        //$data = stripslashes($data);
        //перетворення спецсимволів
        //$data = htmlspecialchars($data);

        return $data;
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

    //метод виведення попереджень при порожніх введенях 
    public static function isEmpty($table_name) {
        //массив назв колонок
        $columns = Helper::getColumnsNames($table_name);
        //массив помилок Array ( [0] => '' [1] => '' [2] => '' ... )
        $errors = array_fill(0, count($columns), '');
        //масив колонка => помилка
        $params = array_combine($columns, $errors);
        foreach ($params as $column => &$error) {
            if (isset($_POST[$column]) && empty($_POST[$column])) {
                $error = "Введіть данні!";
            }
        }
        return array_values($params);
    }

    //метод визначення непорожніх введень форми  
    public static function NotEmptyEnter(): bool {
        // масив данних введенних у форму
        $form_values = (array_slice(array_values($_POST), 0, count($_POST) - 1));

        // якщо хоч одне значення у формі не було введено = FALSE
        /* foreach ($form_values as $form_value)
          {
          if(!$form_value)return FALSE;
          } */
        return TRUE;
    }

    /* метод виведення попереджень при порожніх введенях 
      для окремоого поля */

    public static function isSeparateEmpty($field): array {

        //масив помилок
        $params = array($field => '');
        foreach ($params as $column => &$error) {
            if (isset($_POST[$column])) {
                if (empty($_POST[$column])) {
                    $error = "Введіть данні!";
                }
            }
        }
        return array_values($params);
    }

    //метод отримання id
    public static function getParamFromUrl(string $paramNane) {
        return filter_input(INPUT_GET, $paramNane);
    }

    
    //метод, який реагує на натискання кнопок купити
    public static function buttonListener($products) {
        $names = [];
        for ($i = 0; $i < count($products); $i++) {
            $names[] = $products[$i]['product_id'];
        }
        foreach ($names as $name) {
            if (!empty($_POST[$name])) {
                echo ("<div id ='order'><h3>Товар <strong>" .
                (array_values($_POST)[1]) .
                "</strong> додано до Вашого кошика! </h3></div>");
            }
        }
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
