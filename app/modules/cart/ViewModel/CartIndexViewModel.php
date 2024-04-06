<?php
// app\modules\product\ViewModel\ProductIndexViewModel.php
namespace app\modules\cart\ViewModel;

use app\modules\cart\Model\CartResourceModel;
use app\modules\cart\Model\CartViewer;

class CartIndexViewModel
{    
    private CartResourceModel $cartResourceModel;
    private CartViewer $cartViewer;

    public function __construct()
    {        
        $this->cartResourceModel = new CartResourceModel();
        $this->cartViewer = new CartViewer();
    }   

    public function getResourceModel(): CartResourceModel
    {
        return $this->cartResourceModel;
    }
    
    public function getCartViewer(): CartViewer
    {
        return $this->cartViewer;
    }
}
