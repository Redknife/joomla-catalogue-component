<?php defined('_JEXEC') or die; 
JHtml::_('behavior.caption');

?>

<div class="catalogue-categories">
	<ul class="unstyled">
		<?php foreach ($this->items as $item) : ?>
			<?php
				$registry = new JRegistry();
				$registry->loadString($item->linked_id);
				$item->count += count($registry->toArray());
			?>
			<li class="span3">
				<div class="ticket ticket-new"></div>
				<span class="title"><i class="icon-th"> </i> <?php echo $item->title ?> (<?php echo $item->count; ?>)</span>
				<?php
					$image = $item->image ? $item->image : ($item->subimage ? $item->subimage : false);
					$thumb = $image ? CatalogueHelper::createThumb($item->id, $image, 220, 200, 'min') : 'images/no-image.png'
				?>
				
				<?php if ($item->count) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_catalogue&view=category&cid='.$item->id) ?>"><img src="<?php echo $thumb ?>"/></a>
				<?php else : ?>
					<img src="<?php echo $thumb ?>"/>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>