<?php

/**
 * Class Menu
 */
class Menu extends Model
{
    /**
     * Menu constructor.
     */
    function __construct()
    {
        $this->table_name = "menu";
        $this->id_column = "id";
    }
    
    public function getMenu() {
        return $this->initCollection()
                        ->sort(array('sort_order' => 'ASC'))->getCollection()->select();
    }
}
