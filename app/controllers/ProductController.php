<?php

class ProductController extends Controller {

    public function IndexAction() {
        $this->ListAction();
    }

    // МЕТОД ВИВЕДЕННЯ ВСІХ ТОВАРІВ
    public function ListAction() {
        // Встановлюємо назву сторінки
        $this->setTitle("Товари");

        $this->registry['products'] = /* повертає обєкт класу Product зі значеннями полів
                  table_name = products та id_column = id
                 */
                $this->getModel('Product')
                ->initProductCollection($this->getId('Category'))

                /* метод фільтрування */
                //->filter($this->getSortParams())

                /* метод сортування */
                ->sort($this->getSortParams())

                /* повертає обєкт класу Product зі значенням властивості $collection */
                ->getCollection()

                /* повертає значення властивості $collection класу Product */
                ->select();

        //print_r($this->registry);
        // завантаження вигляду
        $this->setView();

        // завантаження шаблону
        $this->renderLayout();
    }

    // МЕТОД РЕДАГУВАННЯ ТОВАРУ
    public function EditAction() {
        // Встановлюємо назву сторінки
        $this->setTitle("Редагування товару");

        // Повертає об'єкт класу Category extends Model
        $category_model = $this->getModel('Category');

        // Масив імен категорій
        $category_names = $category_model->getOneColumnArray('title');

        // Масив id категорій		
        $category_id = $category_model->getOneColumnArray('category_id');

        // Масив де ключі - id категорій, значення - імена категорій
        $this->registry['categories'] = array_combine($category_id, $category_names);

        // Введений sku 
        $entered_sku = Helper::ClearInput($_POST['sku']);

        // Змінна для виводу помилок 
        $this->registry['error'] = $this->registry['success'] = '';

        // Якщо не адмін
        if (Helper::isAdmin() != 1) {
            $this->registry['error'] = "Ви не маєте права редагувати товари!";
        }

        // Повертає об'єкт класу Product extends Model
        $model = $this->getModel('Product');

        // Отримуємо масив данних товару, що редагується
        $this->registry['product'] = $model->getItem($this->getId('Product'));

        if ($_POST) {
            $model->editItem($this->getId('Product'));
        }
        $this->registry['product'] = $model->getItem($this->getId('Product'));

        //відображаємо вигляд
        $this->setView();

        //відображаємо шаблон
        $this->renderLayout();
    }

    // МЕТОД ДОДАВАННЯ ТОВАРУ
    public function AddAction() {
        // Встановлюємо назву сторінки
        $this->setTitle("Додавання товару");

        // Повертає об'єкт класу Product extends Model
        $model = $this->getModel('Product');

        // Повертає об'єкт класу Category extends Model
        $categoryModel = $this->getModel('Category');

        // Масив де ключі - id категорій, значення - імена категорій
        $this->registry['categories'] = array_combine(
                $categoryModel->getCategoriesIds(), $categoryModel->getCategoriesNames());

        $this->registry['error'] = '';

        // введений sku 
        $entered_sku = $_POST['sku'];

        // Якщо введений sku не унікальний 
        if ($values = $model->getPostValues() && !($model->IsValueExists($entered_sku, "sku"))) {
            //Викликаємо метод класу Model додавання товару 
            $model->addProduct();
        } else {
            $this->registry['error'] = "Товар з sku = $entered_sku вже існує.<br>Введіть інший sku та спробуйте знову";
        }

        //відображаємо вигляд
        $this->setView();

        //відображаємо шаблон
        $this->renderLayout();
    }

    // МЕТОД ВИДАЛЕННЯ ТОВАРУ
    public function DeleteAction() {
        // Встановлюємо назву сторінки
        $this->setTitle("Видалення товару");

        // Повертає об'єкт класу Product extends Model
        $model = $this->getModel('Product');

        // Отримуємо масив данних товару, що редагується 	
        $this->registry['product'] = $model->getItem($this->getId('Product'));

        // Якщо отриманий з запиту id існує в БД - видаляємо
        if (in_array(
                        $this->getId('Product'),
                        $model->getColumnArray($this->getIdColumnName('Product')))) {
            // Викликаємо метод класу Model видалення товару
            $model->deleteItem($this->getId('Product'));

            //відображаємо вигляд
            $this->setView();

            //відображаємо шаблон
            $this->renderLayout();
        }

        // Якщо отриманий з запиту id неіснує в БД    
        else {
            //відображаємо шаблон
            $this->renderPartialview('layout');
            echo("Нема такого товару");
        }
    }

    public function CartAction() {
        // Повертає об'єкт класу Product extends Model
        $model = $this->getModel('Product');

        // Встановлюємо назву сторінки
        $this->setTitle("Додавання до кошику");

        $this->registry['cart'] =
                $model->initCollection()
                ->getCollection()
                ->getItemByParam('id', $this->getId($this->getModelName()));

        $this->setView();
        $this->renderLayout();
    }

    public function getSortParams() {
        //змінна оновлення сторінки (0 - не оновлена, 1 - оновлена)
        $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&
                ($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||
                $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');

        $params = [];

        $sortfirst = filter_input(INPUT_POST, 'sortfirst');

        if ($sortfirst === "price_DESC") {
            $params['price'] = 'DESC';
            //запис cookie для ціни
            setcookie('price', 'DESC', time() + 3600, '/');
        } else {
            $params['price'] = 'ASC';
            //запис cookie для ціни
            setcookie('price', 'ASC', time() + 3600, '/');
        }

        $sortsecond = filter_input(INPUT_POST, 'sortsecond');

        if ($sortsecond === "qty_DESC") {
            $params['qty'] = 'DESC';
            //запис cookie для к-сті
            setcookie('qty', 'DESC', time() + 3600, '/');
        } else {
            $params['qty'] = 'ASC';
            //запис cookie для к-сті
            setcookie('qty', 'ASC', time() + 3600, '/');
        }

        //масив cookie 	
        $cookies = ['price' => isset($_COOKIE['price']) ? $_COOKIE['price'] : $params['price'], 'qty' => isset($_COOKIE['qty']) ? $_COOKIE['qty'] : $params['qty']];

        //якщо сторінку оновили=сортуємо, якщо ні показуємо cookie 
        return $pageRefreshed == 0 ? $cookies : $params;
    }

    //МЕТОД ЕКСПОРТУ З XML	
    public function UnloadAction() {
        $products = $this->getModel('Product')
                        ->initCollection()
                        ->getCollection()->select();

        //повідомлення про помилку або успіх
        $this->registry['export_message'] = '';

        if (isset($_POST['export'])) {
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><products/>');

            foreach ($products as $product) {
                $xmlProduct = $xml->addChild('product');
                $xmlProduct->addChild('id', $product['id']);
                $xmlProduct->addChild('sku', $product['sku']);
                $xmlProduct->addChild('name', $product['name']);
                $xmlProduct->addChild('price', $product['price']);
                $xmlProduct->addChild('qty', $product['qty']);
                $xmlProduct->addChild('description', $product['description']);
            }

            $dom = new DOMDocument("1.0");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($xml->asXML());
            $dom->saveXML();

            $dirName = 'public';

            //якщо папки $dirName немає
            if (!is_dir($dirName)) {
                //створюємо папку $dirName
                mkdir($dirName, 0755);
            }
            $this->registry['export_message'] = 'Файл не вдалося створити!';
            $file = fopen('public/products.xml', 'w');
            fwrite($file, $dom->saveXML());
            fclose($file);
            $this->registry['export_message'] = 'Файл створено успішно!';
        }

        $this->setView();
        $this->renderLayout();
    }

    //МЕТОД ІМПОРТУ З XML	
    public function UploadAction() {
        $products = $this->getModel('Product')
                        ->initCollection()->getCollection()->select();

        //повідомлення про помилку або успіх
        $this->registry['import_message'] = '';

        //отримання масиву id
        $id_array = [];

        foreach ($products as $key => $value) {
            $id_array[] = $value['id'];
        }

        if (isset($_POST['import'])) {
            if (file_exists("public/import.xml")) {
                //завантаження файлу
                $xml = simplexml_load_file("public/import.xml") or die("Помилка завантаження файлу!");
                $invalid_id_array = [];
                //отримання записів з файлу
                foreach ($xml->children() as $row) {
                    $id = $row->id;
                    $sku = $row->sku;
                    $name = $row->name;
                    $price = $row->price;
                    $qty = $row->qty;
                    $description = $row->description;

                    //запис в БД
                    $db = new DB();

                    //якщо ціна та кількість некорректні - пропускаємо і записуємо в маисв
                    if ($price < 0 || $qty < 0) {
                        $invalid_id_array[] = $id;
                        continue;
                    }

                    //якщо id не унікальний - оновлюємо запис
                    elseif (in_array($id, $id_array)) {
                        $sql = "UPDATE products SET id=?,sku=?,name=?,price=?,qty=?,description=? WHERE id=?;";
                        $db->query($sql, array($id, $sku, $name, $price, $qty, $description, $id));
                    }

                    //якщо id унікальний - додаємо запис
                    else {
                        $sql = "INSERT INTO products VALUES (?,?,?,?,?,?);";
                        $db->query($sql, array($id, $sku, $name, $price, $qty, $description));
                    }
                }

                //рядок з id товарів з невалідними параметрами
                $invalid_id_str = implode(",", $invalid_id_array);

                $this->registry['import_message'] = empty($invalid_id_array) ? "Файл імпортовано успішно!" :
                        "Файл імпортовано успішно!<br><br>Товар(и) з id =  $invalid_id_str не були імпортовані
			через некорректні значення ціни або (та) к-сті";
            } else
                $this->registry['import_message'] = "Файлу import.xml в папці public не знайдено! ";
        }

        $this->setView();
        $this->renderLayout();
    }
}
