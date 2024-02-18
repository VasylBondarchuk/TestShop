<?php

class CategoryController extends Controller {

    public function IndexAction() {
        $this->ListAction();
    }

    // МЕТОД ВИВЕДЕННЯ ВСІХ КАТЕГОРІЙ
    public function ListAction() {
        $this->setTitle("Категорії");
        $this->registry['Category'] = $this->getModel('Category')->getCollection();
        $this->setView();
        $this->renderLayout();
    }

    // МЕТОД ПОКАЗУ ТОВАРУ
    public function ShowAction() {
        $this->setTitle("Products");        
        $this->setView();
        $this->renderLayout();
    } 

}
