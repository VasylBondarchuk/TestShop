<?php

/**
 * Class CartController
 */
class CartController extends Controller {

    public function IndexAction() {
        $this->ListAction();
    }

    public function ListAction() {
        $this->setTitle("Додавання до кошику");

        $this->registry['cart'] = $this->getModel('Cart')
                        ->initCollection()
                        ->getCollection()->select();

        $this->setView();
        $this->renderLayout();
    }
    
}
