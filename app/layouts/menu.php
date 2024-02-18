<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <?php
            
            $customer = $this->getModel('Customer');            
            $logedIn = $customer->isLogedIn();            
            
            $registrationOrCustomerFullName= $logedIn
                    ? $customer->getCustomerFullName($customer->getLogedInCustomerId())
                    : 'Реєстрація';

            //вивід написів Увійти та Вийти
            $loginLogOutLabel = $logedIn  ?  ' Вийти ' : ' Увійти '; 

            //вивід для правильних лінків при вході та виході
            $in_out = $logedIn ? 'out' : 'in';
            
            $loginLogOutPath = $logedIn
                    ? $customer->getLogoutPath()
                    : $customer->getLoginPath();
            
            $registrationLink = $logedIn ? '<a/>' : '<a href=' . $customer->getRegisterPath() .'>';

            $cartViewer = $this->getModel('CartViewer');            
            $cartManager = $this->getModel('CartManager');            
            $cartLink = '<a href='. $cartViewer->getPath() .'>';                
            $cartItemsTotalQty = ' (' . $cartManager->getTotalQty() . ')';            
            
            foreach($this->getModel('Menu')->getCollection() as $menuItem):?>
                <li>
                    <?= Helper::urlBuilder($menuItem->getPath(), $menuItem->getName()); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
                    <li><?= $cartLink;?><span class="glyphicon glyphicon-shopping-cart"></span>
                        <?= $cartViewer->getLabel() . $cartItemsTotalQty; ?></a></li>
            <li>
                    <?= $registrationLink;?>
                <span class="glyphicon glyphicon-user"></span> <?= $registrationOrCustomerFullName; ?>
                </a>
            </li>
            <li>
                <a href="<?= $loginLogOutPath;?>/">
                    <span class="glyphicon glyphicon-log-<?= $in_out;?>"></span>
                        <?= $loginLogOutLabel;?>
                </a>
            </li>
        </ul>
    </div>
</nav>
