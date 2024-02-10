<?php

/**
 * Class Cart
 */
class Cart extends Model {

    const CART_PATH = '/cart/list';

    private array $cartItems = [];
    
    private string $cartLabel = '';
    
    private string $cartTitle = '';
    
    private array $cartColumnLabels = [];   
    

    function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->cartItems = $_SESSION['cart'];
        $this->cartLabel = 'Cart';
        $this->cartTitle = 'Your Cart';
        $this->cartColumnLabels = [
            'Product image',
            'Product name',
            'SKU',
            'Price',
            'Qty',
            'Total'
            ];        
    }
    
    // Get cart label
    public function getCartLabel(): string {
        return $this->cartLabel;
    }
    
     // Get cart title
    public function getCartTitle(): string {
        return $this->cartTitle;
    }
    
     // Get cart column labels
    public function getCartColumnLabels(): array {
        return $this->cartColumnLabels;
    }


    // Get cart items
    public function getCartItems(): array {
        return $this->cartItems;
    }

    // Get cart items
    public function isCartEmpty(): bool {
        return empty($this->cartItems);
    }

    // Add item to cart
    public function addToCart(array $itemToAdd) {
        // Check if the product already exists in the cart
        foreach ($this->cartItems as &$item) {
            if ($item['product_id'] === $itemToAdd['product_id']) {
                // Product already exists, update the quantity
                $item['qty'] += $itemToAdd['qty'];
                $this->updateSessionCart();
                return;
            }
        }
        // If the product doesn't exist, add it to the cart
        $this->cartItems[] = $itemToAdd;
        $this->updateSessionCart();
    }

    // Delete item from cart
    public function delCartItem(int $key) {
        if (isset($this->cartItems[$key])) {
            unset($this->cartItems[$key]);
            $this->updateSessionCart();
        }
    }

    // Empty cart
    public function emptyCart() {
        $this->cartItems = [];
        $this->updateSessionCart();
    }

    // Update session cart
    private function updateSessionCart() {
        $_SESSION['cart'] = $this->cartItems;
    }

    // Get total quantity of a specific ordered item
    public function itemTotalQty(int $productId) {
        $cartItemTotalQty = 0;
        foreach (self::getCartItems() as $product) {
            if ($product['product_id'] == $productId) {
                $cartItemTotalQty += $product['qty'];
            }
        }
        return $cartItemTotalQty;
    }

    // Get total amount of a specific ordered item
    public function itemTotalAmount(int $productId, $item_price): float {
        return self::itemTotalQty($productId) * $item_price;
    }

    // Get total quantity of all ordered items
    public function cartTotalQty(): int {
        $totalQty = 0;
        foreach (self::getCartItems() as $cartItem) {
            $totalQty += $cartItem['qty'];
        }
        return $totalQty;
    }

    // Get total amount of all ordered items
    public function cartTotalAmount() {
        $cartTotalAmount = 0;
        foreach (self::getCartItems() as $product) {
            // Total quantity of all ordered items
            $cartTotalAmount += self::itemTotalAmount($product['product_id'], $product['price']);
        }
        return $cartTotalAmount;
    }

    // Get cart path
    public function getCartPath(): string {
        return route::getBP() . self::CART_PATH;
    }
    
}
