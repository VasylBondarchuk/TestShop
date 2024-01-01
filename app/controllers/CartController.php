<?php

/**
 * Class CartController
 */
class CartController extends Controller
{	
	public function IndexAction()
    {
        $this->ListAction();
        $this->ListAction();
		
    }
	
    public function ListAction()
    {
        $this->setTitle("Товари");
        $this->registry['products'] = $this->getModel('Cart')->initCollection()
               ->getCollection()->select();
		
        $this->setView();
        $this->renderLayout();
    }
        	
	 public function addAction()
    {	
		$model = $this->getModel('Cart');
        $this->setTitle("Додавання до кошику");
		
		$this->registry['cart'] = $this->getModel('Cart')
					->initCollection()
                    ->getCollection()->select();
		
		$this->setView();
        $this->renderLayout();			
    }
}   