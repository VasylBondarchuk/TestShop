<?php
if ($this->getModel('Customer')->isAdmin()) {
        echo '<span class="glyphicon glyphicon-pencil"></span>' . " ";
        echo Helper::urlBuilder('/product/edit', 'Edit', array('product_id' => $product->getProductId())) . " ";
        echo '<span class="glyphicon glyphicon-trash"></span>' . " ";
        echo Helper::urlBuilder('/product/delete', 'Delete', array('product_id' => $product->getProductId()));
    } 
