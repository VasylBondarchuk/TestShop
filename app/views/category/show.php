<table>	
	<tr>
		<?php
// Категорії
		$categories=($this->registry['Category']);
		$categories=array_slice($categories,1,count($categories)-1);
		foreach($categories as $category)  : ?>	
			<th><center><?php echo(Helper::simpleLink('/product/list', $category['title'], array('category_id'=>$category['category_id'])));?></center></th>
			
		<?php endforeach; ?>
	</tr>
	<tr>
		<?php
// Категорії
		$categories = ($this->registry['Category']);
		$categories = array_slice($categories,1,count($categories)-1);
		foreach($categories as $category)  : ?>	
			<td width="20%">
				<br><center><img src="<?php echo ("../img/category/".$category['title'].".jpg")?>" alt="<?php echo $category['title']?>" width="200" height=""></center>
			</td>
		<?php endforeach; ?>
	</tr>											
</table>