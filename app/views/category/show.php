<table>	
    <tr>
        <?php
        // Категорії
        $categories = ($this->registry['Category']);
        array_shift($categories);
        foreach ($categories as $category) :
            ?>	
            <th>
        <center>
    <?php echo(Helper::simpleLink('/product/list', $category['category_name'], array('category_id' => $category['category_id']))); ?>
        </center>
    </th>
<?php endforeach; ?>
</tr>

<tr>
    <?php
// Категорії
    $categories = ($this->registry['Category']);
    array_shift($categories);
    foreach ($categories as $category) :
        ?>                        
        <td width="20%">                            
            <br>
    <center>
        <img src="<?php echo ("/img/category/" . $category['category_name'] . ".jpg") ?>" alt="<?php echo $category['category_name'] ?>" width="200" height="">
    </center>
    </td>
<?php endforeach; ?>
</tr>											
</table>