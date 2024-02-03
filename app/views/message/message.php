<?php
if (isset($this->registry['errorMessage'])) : ?>

    <div class ="product">                   
            <?= $this->registry['errorMessage'];?>
    </div>

<?php endif; ?>

<?php if (isset($_SESSION['successMessage'])) : ?>

    <div class="product">
        <center>            
            <?php
            echo $_SESSION['successMessage'];
            unset($_SESSION['successMessage']);
            ?>
        </center>
    </div>
<?php endif; ?>


