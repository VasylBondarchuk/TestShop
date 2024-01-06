<table>	
    
    <?php
    // Категорії
    $categories = $this->getModel('Category')->getCategoriesDetails();
    array_shift($categories);    
        foreach ($categories as $category) : ?>	
        <th>
        <center>
    <?=Helper::simpleLink('/product/list', $category['category_name'], array('category_id' => $category['category_id'])); ?>
        </center>
    </th> 
    <?php endforeach; ?>    
<tr>
    <?php foreach ($categories as $category) : ?>                        
        <td width="20%">                            
            <br>
    <center>
        <img src="<?= "/img/category/" . $category['category_name'] . ".jpg" ?>" alt="<?= $category['category_name'] ?>" width="200" height="">
    </center>
    </td>
<?php endforeach; ?>
    
</tr> 
</table>
