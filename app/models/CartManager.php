<?php

/**
 * Class CartManager
 */
class CartManager {

    private array $cartItems = [];

    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->cartItems = $_SESSION['cart'];
    }

    public function getItems(): array {
        return $this->cartItems;
    }

    public function isEmpty(): bool {
        return empty($this->cartItems);
    }

    public function addItem(CartItem $cartItemToBeAdded): void {
        foreach ($this->cartItems as &$cartItem) {
            // If the product ID wasn found in the cart, increase qty
            if ($cartItem->getProductId() === $cartItemToBeAdded->getProductId()) {
                $cartItem->setQuantity($cartItem->getQuantity() + $cartItemToBeAdded->getQuantity());
                $this->updateSessionCart();
                return;
            }
        }
        // If the product ID wasn't found in the cart, add the item to the cart
        $this->cartItems[] = $cartItemToBeAdded;
        $this->updateSessionCart();
    }

    public function deleteItem(int $key): void {
        if (isset($this->cartItems[$key])) {
            unset($this->cartItems[$key]);
            $this->updateSessionCart();
        }
    }

    public function emptyCart(): void {
        $this->cartItems = [];
        $this->updateSessionCart();
    }

    private function updateSessionCart(): void {
        $_SESSION['cart'] = $this->cartItems;
    }

    // Get total quantity of all ordered items
    public function getTotalQty(): int {
        $totalQty = 0;
        foreach ($this->getItems() as $cartItem) {
            $totalQty += $cartItem->getQuantity();
        }
        return $totalQty;
    }

    // Get total amount of all ordered items
    public function totalAmount() {
        $cartTotalAmount = 0;
        foreach ($this->getItems() as $cartItem) {
            // Total quantity of all ordered items
            $cartTotalAmount += $cartItem->itemTotalAmount($product['product_id'], $product['price']);
        }
        return $cartTotalAmount;
    }
}
