<?php defined('_JEXEC') or die; 
JHtml::_('behavior.caption');

$config = JFactory::getConfig();

$doc = JFactory::getDocument();
$doc->setTitle( $config->get('sitename').' - '.$this->item->category. ' > '. $this->item->name.', купить подарок в Челябинске');

$params = $this->state->get('params');

$app = JFactory::getApplication();
$path = $app->getPathway();

$path->addItem($this->state->get('category.name'), JRoute::_('index.php?option=com_catalogue&view=category&cid='.$this->state->get('category.id')));
$path->addItem($this->state->get('item.name'));

$priceData = new JRegistry();
$priceData->loadString($this->item->params);
$price = $priceData->toArray();

?>

<div class="catalogue-item span9">
	<h1 class="catalogue-item-header"><?php echo $this->item->name ?></h1>
	<div class="row">
		<div class="span8">
			<strong>Описание товара:</strong> <p><?php echo $this->item->desc ?></p>	
		</div>
		<?php if ($price) : ?>
			<div class="span4 data-toggle="buttons-radio">
				<table class="table table-striped table-bordered table-rounded table-prices">
					<tr>
						<th width="50%">Название</th>
						<th width="20%">Цена</th>
						<th width="20%">Цена за штуку</th>
						<th width="1%">#</th>
					</tr>
					<?php foreach($price as $key => $row) : ?>
						<tr>
							
							<td><?php echo $row['name'] ?></td>
							<td><?php echo $row['price'] ?> р.</td>
							<td><?php echo $row['price'] / $row['count']?> р.</td>
							<td><input type="radio" name="radio-group" value="<?php echo $row['price'] ?>" <?php echo $key == 0 ? 'checked="checked"' : ''?>></input></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
			<div class="img span5">
				<a href="<?php echo CatalogueHelper::createThumb($this->item->id, $this->item->image, 220, 200, 'mid') ?>" rel="lightbox"><img src="<?php echo CatalogueHelper::createThumb($this->item->id, $this->item->image, 220, 200, 'mid') ?>"/></a>
			</div>
			<?php else : ?>
			<div class="img span8 align-center">
				<a href="<?php echo CatalogueHelper::createThumb($this->item->id, $this->item->image, 220, 200, 'mid') ?>" rel="lightbox"><img src="<?php echo CatalogueHelper::createThumb($this->item->id, $this->item->image, 220, 200, 'mid') ?>"/></a>
			</div>
			
			<?php endif; ?>
		
		<div>
			<div class="span4">
				<a href="#" class="btn-order" id="btnOrder" ><span>Заказать</span></a>
			</div>
			<div class="span4 align-center">
				<div class="price">К оплате <span id="orderPrice"><?php echo $this->item->price ?></span> руб.</div>
			</div>
			
		</div>
	</div>
	
	
	<?php echo $this->loadTemplate('order') ?>
	
	
	<? if ($params->get('moreitems', 1)) : ?>
	<h3>Похожие товары:</h3>
	<div class="more-items row">
		<ul class="unstyled">
			<?php if ($this->more) foreach ($this->more as $item) : ?>
				<li class="span2"><a href="<?php echo JRoute::_('index.php?option=com_catalogue&view=category&cid='.$this->state->get('category.id').'&id='.$item->id); ?>" ><img src="<?php echo CatalogueHelper::createThumb($item->id, $item->image, 220, 200) ?>"/><span><?php echo $item->name?></span></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
	<? if ($params->get('watcheditems', 1)) : ?>
	<h3>Недавно просмотренные товары:</h3>
	<div class="more-items row">
		<ul class="unstyled">
			<?php if ($this->watchlist) foreach ($this->watchlist as $item) : ?>
				<li class="span2"><a href="<?php echo JRoute::_('index.php?option=com_catalogue&view=category&cid='.$this->state->get('category.id').'&id='.$item->id); ?>" ><img src="<?php echo CatalogueHelper::createThumb($item->id, $item->image, 220, 200) ?>"/><span><?php echo $item->name?></span></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>

</div>