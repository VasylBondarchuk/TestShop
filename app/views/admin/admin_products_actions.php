<?php
$customer = $this->getModel('Customer');
if ($customer->isAdmin()) : ?>
    <div class="product">
        <p>
            <span class="glyphicon glyphicon-plus"></span>
            <?= Helper::urlBuilder('/product/add', 'Додати товар');?>
        </p>
    </div>
    <div class="product">
        <p>
            <span class="glyphicon glyphicon-export"></span>
            <?= Helper::urlBuilder('/product/unload', 'Експорт');?>
        </p>
    </div>
    <div class="product">
        <p>
            <span class="glyphicon glyphicon-import"></span>
            <?= Helper::urlBuilder('/product/upload', 'Імпорт');?>
        </p>
    </div>
<?php endif; ?>


