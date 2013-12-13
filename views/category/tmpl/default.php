<?php defined('_JEXEC') or die; 
JHtml::_('behavior.caption');
$start = microtime(true);

$params = $this->state->get('params');

$app = JFactory::getApplication();
$path = $app->getPathway();
$path->addItem($this->state->get('category.name'), JRoute::_('index.php?option=com_catalogue&view=categories'));


?>

<div class="catalogue-categories">
	<form class="" id="catalogueFilterForm" action="<?php echo JRoute::_('index.php?option=com_catalogue&view=category&cid='.$params->get('id')) ?>">
		<input type="hidden" name="filter_" value="" />
	</form>
	<ul class="unstyled">
		<?php foreach ($this->items as $item) : ?>
			<li class="span3">
				<?php switch ($item->sticker) {
						case 0 : break;
						case 1 : echo '<div class="ticket ticket-new"></div>'; break;
						case 2 : echo '<div class="ticket ticket-hot"></div>'; break;
						case 3 : echo '<div class="ticket ticket-sale"></div>'; break;
					}
					?>
					<span class="title"><?php echo  $item->ishot ? '<i class="icon-fire"></i>' : '<i class="icon-th"></i>' ?> <?php echo $item->name ?></span>
				
				<a href="<?php echo JRoute::_('index.php?option=com_catalogue&view=item&cid='.$item->cat_id.'&id='.$item->id) ?>"><img src="<?php echo CatalogueHelper::createThumb($item->id, $item->image, 220, 200) ?>"/></a>
				<div class="info">
					
					<div class="buttons">
						<div class="btn-group">
							<a href="<?php echo JRoute::_('index.php?option=com_catalogue&view=item&cid='.$item->cat_id.'&id='.$item->id) ?>" class="btn btn-mini btn-<?php echo $params->get('btntype', 'primary') ?>"><i class="icon icon-ok icon-white"> </i> <?php echo $item->price ?> руб.</a>
							<a href="#" id="id-<?php echo $item->id ?>" class="btn btn-mini addtofavorite<?php echo (CatalogueHelper::isFavorite($item->id) ? ' active btn-success' : '') ?>"><i class="icon icon-heart"> </i> В избраннное</a>
						</div>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>