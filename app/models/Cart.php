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

    public function getCartItems() {
        return $_SESSION['cart'] ?? [];
    }

    public function addToCart(array $itemToAdd) {
        // Initialize the cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        // Check if the product already exists in the cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] === $itemToAdd['product_id']) {
                // Product already exists, update the quantity
                $item['qty'] += $itemToAdd['qty'];
                return;
            }
        }
        // If the product doesn't exist, add it to the cart
        $_SESSION['cart'][] = $itemToAdd;
    }

    public function delCartItem(int $itemKey) {
        // Check if the cart exists and is not empty
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            // Loop through each item in the cart
            foreach ($_SESSION['cart'] as $key => $item) {
                // Check if the item key matches the provided item key
                if ($key === $itemKey) {
                    // Remove the item from the cart
                    unset($_SESSION['cart'][$key]);
                    // Break out of the loop since the item has been deleted
                    break;
                }
            }
        }
    }

    public function emptyCart() {
        if (isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
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
    public function itemTotalAmount(int $productId, $item_price) {
        return self::itemTotalQty($productId) * $item_price;
    }

    //загальна кількість всіх замовлених товарів
    public function cartTotalQty(): int {
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
