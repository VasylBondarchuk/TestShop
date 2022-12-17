<?php

class CategoryController extends Controller
{
	public function IndexAction()
	{
	    $this->ListAction();
	} 

    // МЕТОД ВИВЕДЕННЯ ВСІХ КАТЕГОРІЙ
    public function ListAction()
    {
        $this->setTitle("Категорії");
        $this->registry['Product'] = $this->getModel('Product')->getItemUniv('category_id',$this->getId('Category'));
        $this->setView();
        $this->renderLayout();
    }
	
    public function ShowAction()
    {      
        $this->setTitle("Категорії");
        $this->registry['Category'] = $this->getModel('Category')->initCollection()
        ->getCollection()->select();
        $this->setView();
        $this->renderLayout();
    }

}