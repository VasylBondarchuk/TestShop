<?php
// app\modules\product\ViewModel\ProductIndexViewModel.php
namespace app\modules\product\ViewModel;

class ProductIndexViewModel
{
    private string $title;
    private array $productsCollection;

    public function __construct(string $title, array $productsCollection)
    {
        $this->title = $title;
        $this->productsCollection = $productsCollection;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getProductsCollection(): array
    {
        return $this->productsCollection;
    }
}
