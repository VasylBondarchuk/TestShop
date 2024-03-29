<?php

class ProductController extends Controller {

    public function IndexAction() {
        $this->ListAction();
    }

    // МЕТОД ВИВЕДЕННЯ ВСІХ ТОВАРІВ
    public function ListAction() {
        $this->setTitle("Products");
        $this->registry['products'] = $this->getModel('Product')
                ->initProductCollection($this->getCategoryId())
                ->filterByPrice()
                ->sort($this->getSortParams())
                ->getCollection()
                ->select();
        $this->setView();
        $this->renderLayout();
    }

    public function EditAction() {
        $this->setTitle("Edit of Product");

        // Retrieve selected category IDs from the POST data
        $selectedCategoryIds = is_array($_POST['category_id']) ? $_POST['category_id'] : [];

        // Check if the form was submitted
        if (isset($_POST['Edit'])) {
            // Get the product ID from the request
            $productId = $this->getProductId();

            // Get the Product model
            $productModel = $this->getModel('Product');

            // Edit the product
            $productModel->editProduct($productId, $selectedCategoryIds);
        }

        // Set the view and render the layout
        $this->setView();
        $this->renderLayout();
    }

    // МЕТОД ПОКАЗУ ТОВАРУ
    public function ShowAction() {
        $productModel = $this->getModel('Product');
        $product = $productModel->getProductById($this->getProductId());
        $productName = $product ? $product->getName() : '';
        $this->setTitle($productName);
        $this->setView();
        $this->renderLayout();
    }

    public function AddAction() {
        $this->setTitle("Додавання товару");
        $model = $this->getModel('Customer');        
        if ($model->isLogedIn()) {
            $customer = $model->getCustomerById($model->getLoggedInCustomerId());                       
            if ($customer->isAdmin()) {                
                $product = $this->getModel('Product');
                if (isset($_POST['Add'])) {                                     
                    if ($product->isValueUnique(Helper::getPostValue('sku'),'sku')) {
                        $product->addProduct();
                        $productId = $product->getLastId();
                        $product->assignProductToCategories($productId, Helper::getPostValue('category_id'));                        
                        Helper::redirect("/product/edit?product_id=$productId");
                    }
                }
            }
        }
        $this->setView();
        $this->renderLayout();
    }

    // МЕТОД ВИДАЛЕННЯ ТОВАРУ
    public function DeleteAction() {
        // Встановлюємо назву сторінки
        $this->setTitle("Видалення товару");
        // Повертає об'єкт класу Product extends Model
        $product = $this->getModel('Product');
        $productId = $this->getProductId();

        //  echo $product->isValueExists($productId,$product->getIdColumn());exit;
        // Якщо отриманий з запиту id існує в БД - видаляємо 
        // Викликаємо метод класу Model видалення товару            
        if ($product->isValueExists($productId, $product->getIdColumn())) {
            //$product->deleteItem($productId);                        
        }
        // Start output buffering                
        //Helper::redirect("/category/list");            
        //відображаємо вигляд
        $this->setView();
        //відображаємо шаблон
        $this->renderLayout();

        // Якщо отриманий з запиту id неіснує в БД    
        /* else {
          //відображаємо шаблон
          $this->renderPartialview('layout');
          echo("Нема такого товару");
          } */
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

    private function getProductId(): ?int {
        return (int) Helper::getQueryParam('product_id');
    }

    public function getCategoryId(): ?int {
        return (int) Helper::getQueryParam('category_id');
    }
}
