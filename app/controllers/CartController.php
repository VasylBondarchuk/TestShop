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

        $this->setView();
        $this->renderLayout();
    }
    
}
