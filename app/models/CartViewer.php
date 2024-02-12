<?php

/**
 * Class CartViewer
 */
class CartViewer extends Model {

    const CART_PATH = '/cart/list';   
    
    private string $cartLabel = '';
    
    private string $cartTitle = '';
    
    private array $cartColumnLabels = [];   
    

    function __construct() {        
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
    public function getLabel(): string {
        return $this->cartLabel;
    }
    
     // Get cart title
    public function getTitle(): string {
        return $this->cartTitle;
    }
    
     // Get cart column labels
    public function getColumnLabels(): array {
        return $this->cartColumnLabels;
    }

    // Get cart path
    public function getPath(): string {
        return route::getBP() . self::CART_PATH;
    }    
}