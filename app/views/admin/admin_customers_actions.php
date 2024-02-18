<?php

if ($customerModel->isAdmin()) {
    echo '<span class="glyphicon glyphicon-pencil"></span>' . " ";
    echo Helper::urlBuilder('/customer/edit', 'Редагувати', array($id_column_name => $customer[$id_column_name])) . " ";
    echo '<span class="glyphicon glyphicon-trash"></span>' . " ";
    echo Helper::urlBuilder('/customer/delete', 'Видалити', array($id_column_name => $customer[$id_column_name]));
}
?>    