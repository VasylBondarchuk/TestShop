<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <?php
            //вивід напису Регістрація або імені користувача
            $menuOut= isset($_SESSION['first_name']) == "" ? "Реєстрація" : $_SESSION['first_name']." ".$_SESSION['last_name'];

            //вивід написів Увійти та Вийти
            $login_out= isset($_SESSION['first_name']) == "" ? " Увійти" : " Вийти";

            //вивід для правильних лінків при вході та виході
            $in_out = isset($_SESSION['first_name']) == "" ? "in" : "out";

            $register = isset($_SESSION['first_name']) == "" ? "login":"register";

            //лінк на регістрацію
            $link = isset($_SESSION['first_name']) == "" ? "<a href=".route::getBP()."/customer/register/>":"<a/>";

            //лінк на кошик
            $cart = "<a href=".route::getBP()."/cart/add>";
            //загальна к-сть в кошику
            $total_amount = "(" . Helper::cartTotalQty() . ")";

            $menu = Helper::getMenu();
            
            foreach($menu as $item):?>
                <li>
                    <?php echo Helper::simpleLink($item['path'], $item['name']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><?php echo $cart;?><span class="glyphicon glyphicon-shopping-cart"></span> <?php echo "Кошик".$total_amount; ?></a></li>
            <li><?php echo $link;?><span class="glyphicon glyphicon-user"></span> <?php echo $menuOut; ?></a></li>
            <li><a href="<?php echo route::getBP();?>/customer/log<?php echo $in_out;?>/"><span class="glyphicon glyphicon-log-<?php echo $in_out;?>"></span><?php echo $login_out;?></a></li>
        </ul>
    </div>
</nav>
