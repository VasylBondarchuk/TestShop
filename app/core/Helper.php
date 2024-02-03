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

    public static function getModel($name) {
        $model = new $name();
        return $model;
    }

    public static function getMenu() {
        return self::getModel('menu')->initCollection()
                        ->sort(array('sort_order' => 'ASC'))->getCollection()->select();
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

    //виведення попередження для не адміна
    public static function isNotAdmin($message) {
        if (Helper::isAdmin() == 0) {
            echo("<span class='warning'><center><h3>" . $message . "</h3></center></span><br>");
        }
    }

    //перевірка чи відвідувач є адміном
    public static function isAdmin() : bool {
        return isset($_SESSION['id'])
                ? self::getModel('Customer')->getCustomerAdminRole($_SESSION['id'])
                : false; 
    }

    //метод створення кошика
    public static function сartStart() {
        $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        Helper::emptyCart();
        Helper::addTheSame();
        Helper::delCartItem();
    }

    //метод видалення товару
    public static function delCartItem() {
        //видалення позиції
        for ($i = 0; $i < count($_SESSION['cart']); $i++) {
            if (isset($_POST[$i])) {
                unset($_SESSION['cart'][$i]);
                unset($_SESSION['qty'][$i]);
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $_SESSION['qty'] = array_values($_SESSION['qty']);
        }
    }

    //метод очищення кошика
    public static function emptyCart() {
        //якщо натиснуто кнопку очищення - закрити сесії
        if (!empty($_POST['empty'])) {
            $_SESSION['cart'] = [];
            $_SESSION['qty'] = [];
            $_SESSION['total_qty'] = [];
        }
    }

    //метод повторного додавання товару
    public static function addTheSame() {
        //якщо додається товар, який вже є в кошику
        for ($i = 0; $i < count($_SESSION['cart']); $i++) {
            for ($j = 0; $j < count($_SESSION['cart']), $j != $i; $j++) {
                if (empty(array_diff($_SESSION['cart'][$i], $_SESSION['cart'][$j]))) {
                    $_SESSION['qty'][$j] += $_SESSION['qty'][$i];
                }
            }
        }
        $_SESSION['cart'] = array_unique($_SESSION['cart'], SORT_REGULAR);
    }

    //загальна кількість конкретного замовленого товару
    public static function itemTotalQty($num, $max_qty) {
        $item_total_qty = 0;
        $item_total_qty = $_SESSION['qty'][$num] > $max_qty ?
                $max_qty : $_SESSION['qty'][$num];

        return $item_total_qty;
    }

    //загальна сумма конкретного замовленого товару
    public static function itemTotalAmount($num, $max_qty, $item_price) {
        $item_total_amount = 0;
        $item_total_amount = Helper::itemTotalQty($num, $max_qty) * $item_price;
        return $item_total_amount;
    }

    //загальна кількість всіх замовлених товарів
    public static function cartTotalQty() {
        $total_qty = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $num => $product) {
                //загальна кількість всіх замовлених товарів
                $total_qty += Helper::itemTotalQty($num, $product['qty']);
            }
        }
        return $total_qty;
    }

    //загальна сумма всіх замовлених товарів
    public static function cartTotalAmount() {
        $total_amount = 0;

        foreach ($_SESSION['cart'] as $num => $product) {
            //загальна кількість всіх замовлених товарів
            $total_amount += Helper::itemTotalQty($num, $product['qty']) * $product['price'];
        }
        return $total_amount;
    }

    //оформлення замовлення
    public static function orderDetails() {
        //час оформлення замовлення
        $datetime = date_create()->format('Y-m-d H:i:s');

        //id замовника
        $customer_id = $_SESSION['id'];

        //змінні замовлення
        $orderitem_id = $order_id = $product_id = $qty = '';

        //максим. id замовлення на момент замовлення						
        $max_order_id = Helper::MaxValue('order_id', 'sales_orderitem') != null ?
                Helper::MaxValue('order_id', 'sales_orderitem') : 0;

        //запис в БД		
        $db = new DB();
        $sql = "INSERT INTO sales_order VALUES (?,?,?);";
        $db->query($sql, array($order_id, $customer_id, $datetime));

        foreach ($_SESSION['cart'] as $num => $product) {

            //максим. id позиції в межах одного замовлення						
            $max_orderitem_id = Helper::MaxValue('orderitem_id', 'sales_orderitem') != null ? Helper::MaxValue('orderitem_id', 'sales_orderitem') : 0;

            //збільшуємо id нового замовлення
            $order_id = $max_order_id + 1;
            $orderitem_id = $max_orderitem_id + 1;

            //id замовленого товару
            $product_id = $product['id'];

            //к-сть замовленого товару
            $qty = Helper::itemTotalQty($num, $product['qty']);

            //запис в БД									
            $sql = "INSERT INTO sales_orderitem VALUES (?,?,?,?);";
            $db->query($sql, array($orderitem_id, $order_id, $product_id, $qty));
        }
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
}
