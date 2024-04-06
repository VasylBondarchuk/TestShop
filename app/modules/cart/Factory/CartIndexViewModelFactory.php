<?php
// app\modules\product\Factory\ProductIndexViewModelFactory.php
namespace app\modules\cart\Factory;

use app\modules\cart\ViewModel\CartIndexViewModel;
use app\modules\cart\Model\CartResourceModel;
use app\modules\cart\Model\CartViewer;

class CartIndexViewModelFactory
{
    public static function create(): CartIndexViewModel
    {
        $cartResourceModel = new CartResourceModel();
        $cartViewer = new CartViewer();
        return new CartIndexViewModel($cartResourceModel, $cartViewer);
    }
}
