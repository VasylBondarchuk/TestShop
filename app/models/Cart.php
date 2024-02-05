<?php

/**
 * Class Product
 */
class Cart extends Model {

    const CART_PATH = '/cart/list';

    /**
     * Product constructor.
     */
    function __construct() {
        $this->table_name = "product";
        $this->id_column = "id";
    }

    // Масив id категорій
    public function getCartPath(): string {
        return route::getBP() . self::CART_PATH;
    }

    // Масив id категорій
    public function getCartLabel(): string {
        return 'Cart';
    }

    //метод створення кошика
    public function addToCart(array $itemToAdd) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        // Add the item to the cart
        $_SESSION['cart'][] = $itemToAdd;
    }

    //метод видалення товару
    public function delCartItem() {
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
    public function emptyCart() {
        //якщо натиснуто кнопку очищення - закрити сесії
        if (isset($_POST['empty'])) {
            $_SESSION['cart'] = [];            
        }
    }

    public function getCartItems() {
        return $_SESSION['cart'] ?? [];
    }

    public function getUniqueCartItems() {
        $uniqueCartItems = [];

        foreach (self::getCartItems() as $product) {
            $productId = $product['product_id'];

            // Check if the product_id is not already in the uniqueElements array
            if (!isset($uniqueCartItems[$productId])) {
                $uniqueCartItems[$productId] = $product;
            } else {
                // If the product_id already exists, update quantity or perform other logic
                $uniqueCartItems[$productId]['qty'] += $product['qty'];
            }
        }
        return array_values($uniqueCartItems);
    }

    //загальна кількість конкретного замовленого товару
    public function itemTotalQty(int $productId) {
        $cartItemTotalQty = 0;
        foreach (self::getCartItems() as $product) {
            if ($product['product_id'] == $productId) {
                $cartItemTotalQty += $product['qty'];
            }
        }
        return $cartItemTotalQty;
    }

    //загальна сумма конкретного замовленого товару
    public function itemTotalAmount(int $productId, $item_price ) { 
        return self::itemTotalQty($productId) * $item_price;
    }

    //загальна кількість всіх замовлених товарів
    public function cartTotalQty() : int {
        $totalQty = 0;
        foreach (self::getCartItems() as $cartItem) {
            $totalQty += $cartItem['qty'];
        }
        return $totalQty;
    }

    //загальна сумма всіх замовлених товарів
    public function cartTotalAmount() {
        $cartTotalAmount = 0;
        foreach (self::getCartItems() as $product) {
            //загальна кількість всіх замовлених товарів
            $cartTotalAmount += self::itemTotalAmount($product['product_id'], $product['price']);
        }
        return $cartTotalAmount;
    }

    //оформлення замовлення
    public function orderDetails() {
        //час оформлення замовлення
        $datetime = date_create()->format('Y-m-d H:i:s');

        //id замовника
        $customer_id = $_SESSION['id'];

        //змінні замовлення
        $orderitem_id = $order_id = $product_id = $qty = '';

        //максим. id замовлення на момент замовлення						
        $max_order_id = self::MaxValue('order_id', 'sales_orderitem') != null ?
                self::MaxValue('order_id', 'sales_orderitem') : 0;

        //запис в БД		
        $db = new DB();
        $sql = "INSERT INTO sales_order VALUES (?,?,?);";
        $db->query($sql, array($order_id, $customer_id, $datetime));

        foreach ($_SESSION['cart'] as $num => $product) {

            //максим. id позиції в межах одного замовлення						
            $max_orderitem_id = self::MaxValue('orderitem_id', 'sales_orderitem') != null ? self::MaxValue('orderitem_id', 'sales_orderitem') : 0;

            //збільшуємо id нового замовлення
            $order_id = $max_order_id + 1;
            $orderitem_id = $max_orderitem_id + 1;

            //id замовленого товару
            $product_id = $product['id'];

            //к-сть замовленого товару
            $qty = self::itemTotalQty($num, $product['qty']);

            //запис в БД									
            $sql = "INSERT INTO sales_orderitem VALUES (?,?,?,?);";
            $db->query($sql, array($orderitem_id, $order_id, $product_id, $qty));
        }
    }
}
