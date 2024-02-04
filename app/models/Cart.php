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
}
