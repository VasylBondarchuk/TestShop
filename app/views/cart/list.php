<?php
// Include necessary files
$cartManager = $this->getModel('CartManager');
$cartViewer = $this->getModel('CartViewer');

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['empty'])) {
        $cartManager->emptyCart();
    } elseif (isset($_POST['delete_item']) && is_numeric($_POST['delete_item'])) {
        $cartItemIndex = $_POST['delete_item'];
        $cartManager->deleteItem($cartItemIndex);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shopping Cart</title>               
    </head>
    <body>
        <div class="container">
            <?php if ($cartManager->isEmpty()): ?>
                <p><?= $cartViewer->getTitle() . ' is empty'; ?></p>
            <?php else: ?>
                <form method="POST" class="cart-form">
                    <input class="w3-button w3-black" name="empty" type="submit" value="Empty Cart"/>
                </form>
                <h1><?= $cartViewer->getTitle(); ?></h1>
                <table>
                    <thead>
                        <tr>
                            <?php foreach ($cartViewer->getColumnLabels() as $columnLabel): ?>                                            
                                <th><?= $columnLabel ?></th>  
                            <?php endforeach; ?>                   
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartManager->getItems() as $cartItemIndex => $cartItem): ?>
                            <tr>                        
                                <td width="20%"><img src="<?= PRODUCT_IMAGE_PATH . $cartItem->getProductImage() ?>"
                                                     alt="<?= $cartItem->getName(); ?>" class="product-image"></td>
                                <td><?= $cartItem->getName(); ?></td>
                                <td><?= $cartItem->getSku(); ?></td>
                                <td><?= $cartItem->getPrice(); ?> грн </td>
                                <td><?= $cartItem->getQuantity(); ?> шт.</td>
                                <td><?= $cartItem->getItemTotalAmount(); ?> грн </td>
                                <td>
                                    <form method="POST" class="delete-form">            
                                        <button type="submit" name="delete_item" value="<?= $cartItemIndex; ?>" class="btn-delete">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </form> 
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </body>
</html>
