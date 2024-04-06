<?php
// app\modules\product\ViewModel\ProductIndexViewModel.php
namespace app\modules\category\ViewModel;

class CategoryIndexViewModel
{    
    private array $categoryCollection;

    public function __construct(array $categoryCollection)
    {        
        $this->categoryCollection = $categoryCollection;
    }   

    public function getCategoryCollection(): array
    {
        return $this->categoryCollection;
    }
}
