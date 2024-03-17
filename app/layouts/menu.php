<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <?php
            
            $customerModel = $this->getModel('app\models\Customer');            
            $logedIn = $customerModel->isLogedIn();                      
            $customer = $logedIn ? $customerModel->getCustomerById($customerModel->getLoggedInCustomerId()) : null;
            $registrationOrCustomerFullName= $logedIn
                    ? $customer->getCustomerFullName()
                    : 'Registration';

            //вивід написів Увійти та Вийти
            $loginLogOutLabel = $logedIn  ?  ' Вийти ' : ' Увійти '; 

            //вивід для правильних лінків при вході та виході
            $in_out = $logedIn ? 'out' : 'in';
            
            $loginLogOutPath = $logedIn
                    ? $customerModel->getLogoutPath()
                    : $customerModel->getLoginPath();
            
            $registrationLink = $logedIn ? '<a/>' : '<a href=' . $customerModel->getRegisterPath() .'>';

            $cartViewer = $this->getModel('app\models\CartViewer');            
            $cartManager = $this->getModel('app\models\CartManager');            
            $cartLink = '<a href='. $cartViewer->getPath() .'>';                
            $cartItemsTotalQty = ' (' . $cartManager->getTotalQty() . ')';            
            
            foreach($this->getModel('app\models\Menu')->getCollection() as $menuItem):?>
                <li>
                    <?= app\core\Helper::urlBuilder($menuItem->getPath(), $menuItem->getName()); ?>
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
