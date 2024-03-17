<?php


class Edit extends Controller {    

    public function action() {
        $this->setTitle("Edit of Product");

        // Retrieve selected category IDs from the POST data
        $selectedCategoryIds = is_array($_POST['category_id']) ? $_POST['category_id'] : [];

        // Check if the form was submitted
        if (isset($_POST['Edit'])) {
            // Get the product ID from the request
            $productId = $this->getProductId();

            // Get the Product model
            $productModel = $this->getModel('Product');

            // Edit the product
            $productModel->editProduct($productId, $selectedCategoryIds);
        }

        // Set the view and render the layout
        $this->setView();
        $this->renderLayout();
    }
}    