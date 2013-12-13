<?php defined('_JEXEC') or die; 
JHtml::_('behavior.caption');
JHtml::_('bootstrap.tooltip');

$i = 0;
$sc = 0; //start category ID

?>

<div class="catalogue-categories">
	<div class="page-header">
		<h1><?php echo $this->items[0]->section ?></h1>
	</div>
	<ul class="unstyled">
		<?php foreach ($this->items as $item) : $i++; ?>
		
			<?php if ($sc != $item->cat_id) : $sc = $item->cat_id; ?>
				<?php if ($i != 1) : ?>
					</ul></li>
				<?php endif; ?>
				
				<li class="catalogue-category"><h2 class="title"><a href="<?php echo JRoute::_('index.php?option=com_catalogue&view=category&cid='.$item->cat_id) ?>"><?php echo $item->category ?></a></h2>
				<?php
					$thumb = $item->image ? CatalogueHelper::createThumb($item->id, $item->image, 220, 200, 'min') : 'images/no-image.png'
				?>
				<div class="head-item row">
					<div class="image span2">
						<a href="<?php echo JRoute::_('index.php?option=com_catalogue&view=category&cid='.$item->cat_id) ?>"><img src="<?php echo $thumb ?>"/><p style="text-align:center"><?php echo $item->name ?></p></a>
					</div>
					<div class="span5">
						<?php echo $item->category_desc ?> 
					</div>
				</div>
				<div class="clearfix"></div>
				<ul class="catalogue-items unstyled">
			<?php else : ?>
				<?php
					$thumb = $item->image ? CatalogueHelper::createThumb($item->id, $item->image, 220, 200, 'min') : 'images/no-image.png'
				?>
				<li class="item-mini span1"><a title="Цена: <?php echo $item->price ?> р." class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_catalogue&view=item&cid='.$item->cat_id.'&id='.$item->id) ?>"><img src="<?php echo $thumb ?>"/><?php echo $item->name ?></a></li>
			<?php endif; ?>
			
			
		<?php endforeach; ?>
		
	</li></ul>
</div>