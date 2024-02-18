<table>	
    
    <?php
    // Категорії
    $categories = $this->getModel('Category')->getCollection();       
        foreach ($categories as $category) : ?>	
        <th>
        <center>
    <?=Helper::urlBuilder('/category/show', $category->getCategoryName(), array('category_id' => $category->getCategoryId())); ?>
        </center>
    </th> 
    <?php endforeach; ?>    
<tr>
    <?php foreach ($categories as $category) : ?>                        
        <td width="20%">                            
            <br>
    <center>
        <img src="<?= CATEGORY_IMAGE_PATH . $category->getCategoryName() . ".jpg" ?>" alt="<?= $category->getCategoryName() ?>" width="200" height="">
    </center>
    </td>
<?php endforeach; ?>
    
</tr> 
</table>
