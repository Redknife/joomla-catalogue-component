<?php defined('_JEXEC') or die; 
JHtml::_('behavior.caption');
$start = microtime(true);
?>

<div class="catalogue-categories">
<ul>
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
			
			<a href="?view=item&id=<?php echo $item->id ?>"><img src="<?php echo CatalogueHelper::createThumb($item->id, $item->image, 220, 200) ?>"/></a>
			<div class="info">
				<ul>
					<li>Цена: <span class="price"><?php echo $item->price ?> руб.</span></li>
				</ul>
				<div class="buttons">
				<?php if (!CatalogueHelper::inCart($item->id)) : ?>
					
						<div class="btn-group">
							<button id="b<?php echo $item->id ?>" class="btn btn-mini btn-primary addtocart">Заказать</button>
						</div>
				<?php else : ?>
					<span class="label label-success"><i class="icon-shopping-cart icon-white"></i> В корзине</span>
				<?php endif; ?>
				</div>
			</div>
		</li>
		
	<?php endforeach; ?>
</ul>
</div>