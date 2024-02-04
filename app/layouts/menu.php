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

            $cart = $this->getModel('Cart');            
            $cartLink = '<a href='. $cart->getCartPath() .'>';
            $cartLabel = $cart->getCartLabel();            
            $cartItemsTotalQty = ' (' . Helper::cartTotalQty() . ')';            
            
            foreach($this->getModel('Menu')->getMenu() as $menuItem):?>
                <li>
                    <?= Helper::simpleLink($menuItem['path'], $menuItem['name']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><?= $cartLink;?><span class="glyphicon glyphicon-shopping-cart"></span> <?= $cartLabel . $cartItemsTotalQty; ?></a></li>
            <li><?= $registrationLink;?><span class="glyphicon glyphicon-user"></span> <?= $registrationOrCustomerFullName; ?></a></li>
            <li>
                <a href="<?= $loginLogOutPath;?>/">
                    <span class="glyphicon glyphicon-log-<?= $in_out;?>"></span>
                        <?= $loginLogOutLabel;?>
                </a>
            </li>
        </ul>
    </div>
</nav>
