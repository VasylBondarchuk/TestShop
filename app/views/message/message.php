<?php if (isset($this->registry['errorMessage'])) : ?>

    <div class="product">
        <center>            
            <?= $this->registry['errorMessage'];?>
        </center>
    </div>    
<?php endif; ?>

<?php if (isset($this->registry['successMessage'])) : ?>

    <div class="product">
        <center>            
            <?= $this->registry['successMessage'];?>
        </center>
    </div>    
<?php endif; ?>


