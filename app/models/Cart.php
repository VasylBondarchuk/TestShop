<?php

/**
 * Class Product
 */
class Cart extends Model
{

    /**
     * Product constructor.
     */
    function __construct()
    {
        $this->table_name = "product";
        $this->id_column = "id";
    }
   
}