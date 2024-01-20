<?php

class CategoryController extends Controller {

    public function IndexAction() {
        $this->ListAction();
    }

    // МЕТОД ВИВЕДЕННЯ ВСІХ КАТЕГОРІЙ
    public function ListAction() {
        $this->setTitle("Категорії");
        $this->registry['Category'] = $this->getModel('Category')->initCollection()
                        ->getCollection()->select();
        $this->setView();
        $this->renderLayout();
    }    
}
