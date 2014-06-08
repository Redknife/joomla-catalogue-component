<?php defined('_JEXEC') or die; 
JHtml::_('behavior.caption');

$config = JFactory::getConfig();

// Загрузка глобальных параметров каталога
$params = $this->state->get('params');

$catalague_order_type = $params->get('catalague_order_type', 1);
$addprice = $params->get('addprice', 0);
$slice_desc = $params->get('slice_desc', 0);
$slice_len = $params->get('short_desc_len', 150);
$img_width = $params->get('img_width', 250);
$img_height = $params->get('img_height', 250);

$item = $this->item;

$app = JFactory::getApplication();
$path = $app->getPathway();

$path->addItem($this->state->get('item.name'));

$mypath = $path->getPathway();
$mypath['0']->name = 'Каталог';
$mypath['0']->link = '#';
$path->setPathway($mypath);

$doc = JFactory::getDocument();
$doc->setTitle( $config->get('sitename').' - '.$item->item_name);

//Технические характеристики
$item_params = new JRegistry($item->params);
$item->params = $item_params->toArray();

?>
<div class="row">
	
	
	<div id="item-open" class="catalogue-item span9">
		<div class="page-header">
			<h2 class="item-name"><?php echo $item->item_name; ?></h2>
		</div>
		<div class="white-box">
			
			<div class="item-image">
				<a href="#" class="slider-prev slider-arrow"></a>
				<div class="item-slider-wrap">
					<ul id="slider-container" class="unstyled">
					<?php if ($item->item_image) : ?>
						<li><img src="<?php $imgpath = CatalogueHelper::createThumb($item->id, $item->item_image, 235, 215, 'mid'); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.jpg'; }  ?>"/></li>
					<?php endif; ?>
					<?php if($item->item_image_2) : ?>
						<li><img src="<?php $imgpath = CatalogueHelper::createThumb($item->id, $item->item_image_2, 235, 215, 'mid'); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.jpg'; }  ?>"/></li>
					<?php endif; ?>
					<?php if($item->item_image_3) : ?>
						<li><img src="<?php $imgpath = CatalogueHelper::createThumb($item->id, $item->item_image_3, 235, 215, 'mid'); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.jpg'; }  ?>"/></li>
					<?php endif; ?>
					<?php if($item->item_image_4) : ?>
						<li><img src="<?php $imgpath = CatalogueHelper::createThumb($item->id, $item->item_image_4, 235, 215, 'mid'); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.jpg'; }  ?>"/></li>
					<?php endif; ?>
					<?php if($item->item_image_5) : ?>
						<li><img src="<?php $imgpath = CatalogueHelper::createThumb($item->id, $item->item_image_5, 235, 215, 'mid'); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.jpg'; }  ?>"/></li>
					<?php endif; ?>
					</ul>
				</div>
				<a href="#" class="slider-next slider-arrow"></a>
			</div>
			<div class="info">
				<div class="price-wrap">
					<span class="item-price"><?php echo number_format($item->price, 2, '.', ' ').' руб.'; ?></span>
					<?php if ($catalague_order_type == 1) : ?>
						<?php if (!CatalogueHelper::inCart($item->id)) : ?>
							<a href="index.php?option=com_catalogue&view=item&task=cart.add_to_cart&id=<?php echo $item->id; ?>" class="addToCart">Заказать</a></td>
						<?php else : ?>
							<a data-id="<?php echo $item->id; ?>" class="addToCart inCart">В корзине</a>
						<?php endif; ?>
					<?php elseif($catalague_order_type == 2) : ?>
					
						<a href="#orderModal" role="button" class="red-btn" data-toggle="modal">Купить в 1 клик</a>
						
					<?php elseif($catalague_order_type == 3) : ?>
						
						<?php if (!CatalogueHelper::inCart($item->id)) : ?>
							<a href="index.php?option=com_catalogue&task=order&orderid=<?php echo $item->id; ?>&Itemid=114&backuri=<?php echo base64_encode(JURI::current()); ?>" class="addToCart red-btn">Заказать</a></td>
						<?php else : ?>
							<a id="b<?php echo $item->id; ?>" data-id="<?php echo $item->id; ?>" class="addToCart inCart red-btn">В корзине</a>
						<?php endif; ?>
						
						<a href="#orderModal" role="button" class="red-btn" data-toggle="modal">Купить в 1 клик</a>
					<?php endif; ?>
				</div>
				<div class="instock">
					<span><?php echo ($item->item_count ? JText::_('COM_CATALOGUE_INSTOCK') : JText::_('COM_CATALOGUE_NOTINSTOCK') ) ?></span>
				</div>
				
			</div>

			<div class="params">
				<h3>Технические характеристики</h3>
				<table id="paramsTable" class="table table-striped table-condensed">
					<?php foreach($item->params as $param) : ?>
						<tr>
							<td><?php echo $param['name'] ?></td>
							<td style="width: 10%"><?php echo $param['value'] ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		
			<div class="desc">
				<h3>Описание <?php echo $item->item_name; ?></h3>
				<?php echo $item->item_description; ?>
			</div>

			<div class="clearfix"></div>
		</div>
	</div>
</div>

<!-- WatchList -->

<?php if($params->get('watcheditems_enable', 0)) : ?>
	<ul class="unstyled">
	<?php foreach($this->watchlist as $w_item) : ?>
		<?php 
			$ilink 	= JRoute::_( 'index.php?'.$w_item->path.'&id='.$w_item->id );
			$src	= CatalogueHelper::createThumb($w_item->id, $w_item->item_image, $img_width, $img_height); 
			?>
		<li class="span3">
			<div class="white-box">

					<a href="<?php echo $ilink ?>">
						<span class="item-name"><?php echo $w_item->item_name ?></span>
						<?php if ($src) : ?>
							<img src="<?php $imgpath = CatalogueHelper::createThumb($w_item->id, $w_item->item_image, 235, 215, 'mid'); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.jpg'; }  ?>"/>
						<?php endif; ?>
					</a>
					
					<div class="price-wrap">
						<span class="item-price"><?php echo number_format($w_item->price, 2, '.', ' ').' руб.'; ?></span>
					</div>
					
					
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
	<div class="clearfix"></div>
<?php endif; ?>

<!-- Modal -->
<div id="orderModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">

  <div class="modal-body">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="modal-image">
	<img src="<?php $imgpath = CatalogueHelper::createThumb($item->id, $item->item_image, 218, 140, 'min'); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.jpg'; }  ?>"/>
	<span class="modal-item-name"><?php echo $item->item_name; ?></span>
	<span class="modal-item-price"><?php echo number_format($item->price, 2, '.', ' ').' руб.'; ?></span>
    </div>
    <div class="order-form">
	<h3 id="orderModalLabel">Оформить покупку</h3>
	<form>
		<div class="control-group">
			
			<div class="controls">
				<input type="text" id="inputName" placeholder="Иван Петров">
			</div>
			<div class="controls">
				<input type="text" id="inputEmail" placeholder="ivan_petrov@mail.ru">
			</div>
			<div class="controls">
				<input type="text" id="inputPhone" placeholder="+7 919 123-45-67">
			</div>
		</div>
		
		<a href="#" class="red-btn">Купить</a>
	</form>
    </div>
  </div>
    
</div>