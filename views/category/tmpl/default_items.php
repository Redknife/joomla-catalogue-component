<?php defined('_JEXEC') or die;

$supersection_id 	= $this->state->get('supersection.id');
$section_id 		= $this->state->get('section.id');
$category_id 		= $this->state->get('category.id');

$params = $this->state->get('params');

$addprice = $params->get('addprice', 0);
$slice_desc = $params->get('slice_desc', 0);
$slice_len = $params->get('short_desc_len', 150);
$img_width = $params->get('img_width', 0);
$img_height = $params->get('img_height', 0);

$num_columns = $params->get('num_columns', 3);
$span_suffix = 12 / $num_columns;
?>


<?php foreach ($this->items as $k => $item) : ?>
	<?php 
	
		$col = $k % $num_columns + 1; 
		$row = floor($k / $num_columns) + 1;
		$ilink 	= JRoute::_( 'index.php?option=com_catalogue&view=item&cid='.$item->category_id.'&id='.$item->id );
    	//$clink	= CatalogueHelperRoute($item->category_id);
    	$src	= CatalogueHelper::createThumb($item->id, $item->item_image, $img_width, $img_height);
    	if (!$src) $src = '/templates/blank_j3/img/imgholder.jpg';
	        	
	?>
		<?php if ($col == 1) : ?>
			<ul class="unstyled row-fluid">
		<?php endif;?>
		
		        <li class="span<?php echo $span_suffix ?> row-<?php echo $row ?> col-<?php echo $col ?>">
		        	<div class="catalogue-item" itemscope="" itemtype="http://schema.org/Product">	        
		                <a href="<?php echo $ilink; ?>" class="item-link" title="<?php echo $item->item_name; ?>">
		                        <img src="<?php echo $src ?>" title="<?php echo $item->item_name; ?>" alt="<?php echo $item->item_name; ?>" itemprop="image" />
		                </a>
		                
		                <div class="item-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer"> 
		                	<span itemprop="price" class="price product-price"> <?php echo $item->price.' '. $params->get('catalogue_currency', 'руб.'); ?> </span>
		                	<meta itemprop="priceCurrency" content="0">
		                </div>
		                
		                <h5 itemprop="name"> 
		                	<a class="product-name" href="<?php echo $ilink; ?>" title="<?php echo $item->item_name; ?>" itemprop="url"><?php echo $item->item_name; ?></a>
		                </h5>
		                
		                <div class="info">
		                	<?php if ($item->item_description) : ?>
		                        <p class="item-desc">
		                                <?php if ($slice_desc) : ?>
		                                        <?php echo mb_substr(strip_tags($item->item_description), 0, $slice_len).' ...'; ?>
		                                <?php else : ?>
		                                        <?php echo $item->item_description ?>
		                                <?php endif; ?>
		                        </p>
		                    <?php endif; ?>
		                    
		                        
		                        <?php if (!CatalogueHelper::inCart($item->id)) : ?>
		                        <a href="index.php?option=com_catalogue&task=order&orderid=<?php echo $item->id; ?>&backuri=<?php echo base64_encode(JURI::current()); ?>" class="addToCart">Заказать</a></td>
		                <?php else : ?>
		                        <a id="b<?php echo $item->id; ?>" data-id="<?php echo $item->id; ?>" class="addToCart inCart">В корзине</a>
		                <?php endif; ?>
		
		                </div>
	                </div>
		        </li>
        
        <?php if ($col == $num_columns) : ?>
			</ul>
		<?php endif;?>
<?php endforeach; ?>
