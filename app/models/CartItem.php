<?php

/**
 * Class CartItem
 */
class CartItem {

    private int $productId;
    private string $sku;
    private string $name;
    private float $price;
    private int $quantity;
    private string $productImage;

    public function __construct(
            int $productId,
            string $sku,
            string $name,
            float $price,
            int $quantity,
            string $productImage) {
        $this->productId = $productId;
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->productImage = $productImage;
    }

    // Getter methods for all properties
    public function getProductId(): int {
        return $this->productId;
    }

    public function getSku(): string {
        return $this->sku;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getProductImage(): string {
        return $this->productImage;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    // Get total amount of a specific ordered item
    public function getItemTotalAmount(): float {
        $quantity = $this->getQuantity();
        $price = $this->getPrice();

        // Validate quantity and price
        if (!is_numeric($quantity) || !is_numeric($price) || $quantity < 0 || $price < 0) {
            // Handle invalid quantity or price
            throw new InvalidArgumentException('Invalid quantity or price for item');
        }

        // Calculate total amount
        return $quantity * $price;
    }
}
