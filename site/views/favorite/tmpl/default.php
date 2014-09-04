<?php defined('_JEXEC') or die; 

$items = CatalogueHelper::getFavoriteItems();
?>
<div class="span9">
	<div class="page-header">
		<h2>Избранное</h2>
	</div>
	<table class="table table-condensed table-favorite">
		<thead>
			<tr>
				<th>#</th>
				<th class="span3">Изображение</th>
				<th class="span5">Описание</th>
				<th>Цена</th>
			</tr>
		<thead>
		<?php if ($items) : foreach ($items as $item) : $summ += $item->price; ?>
			<tr id="t<?php echo $item->id?>" class="cart-row">
				<td width="1%"><?php echo $i += 1 ?></td>
				<td><img class="thumbnail span2" src="<?php echo $item->image ?>"/></td>
				<td class="span2"><?php echo $item->name ?>
				<p style="font-size: 0.9em"><?php echo JFilterOutput::cleanText($item->desc) ?></p></td>
				<td class="span1"><span class="row-price badge badge-warning"><?php echo $item->price ?> р.</span></td>
							
			</tr>
		<?php endforeach; endif; ?>
	</table>
</div>