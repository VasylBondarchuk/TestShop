<?php
namespace app\modules\category;

use app\core\Collection;

class CategoryCollection extends Collection
{
    // You can add additional methods or override existing ones here
    /*public function filterByCategory($category): self
    {
        $filteredItems = array_filter($this->items, function ($product) use ($category) {
            return $product->getCategory() === $category;
        });

        return new self($filteredItems);
    }/

    // Add more methods as needed for product-specific operations
}
