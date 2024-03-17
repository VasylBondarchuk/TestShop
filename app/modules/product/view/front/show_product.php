<div class="product">
        <table style="width:100%">
            <tr>
                <td width="40%">
                    <img src="<?= PRODUCT_IMAGE_PATH . $product->getProductImage() ?>" alt="<?= $product->getName() ?>" width="500" height="">
                </td>
            <center><h1> <?= $product->getName(); ?></h1></center>
            <td width="60%>                    
                <p class="sku"> SKU: <?= $product->getSku(); ?></p>                    
                <p> Ціна: <span class="price"><?= $product->getPrice() ?></span> грн</p>
                <p> <?php
                    if ($product->getQty() != 0) {
                        echo("Qty:" . $product->getQty() . "");
                    } else {
                        echo('Нема в наявності');
                    }
                    ?>
                </p>
                <p> Опис: <?= htmlspecialchars_decode($product->getDescription()) ?></p>
                <form method="POST" >
                    <input type="number" name="qty" min="1" max="<?= $product->getQty(); ?>" value="1"/>
                    <button <?php if ($product->getQty() == 0) echo("disabled"); ?> class="w3-button w3-black">Buy</button>
                    <input type="hidden" name="<?= $product->getProductId() ?>" value="<?= $product->getName() ?>"/>
                </form>                
            </td>
            </tr>
        </table>        
</div>    



