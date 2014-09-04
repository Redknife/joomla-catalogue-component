<?php defined('_JEXEC') or die;
JHtml::_('behavior.caption');
$app = JFactory::getApplication();
$params = $app->getParams();
// require_once(JPATH_COMPONENT . '/helpers/route.php');
?>
<div class="page-header">
	<h1>Каталог офисной мебели</h1>
</div>

<div class="gray-info-box">
	<div class="pull-left big-info">90%</div>
	<div class="pull-left small-info">
		клиентов обращаются к нам повторно. Покупка офисной мебели в «АПС Технолоджи» - гарантия качества и уверенность
в правильном выборе мебели.
	</div>
	<div class="clearfix"></div>
</div>

<div class="catalogue-categories">
	<div class="row">
		<?php foreach ($this->items as $item) : ?>
			<?php $img = json_decode($item->params)->image; ?>
			<?php $ilink = ''; ?>
		        <div class="span4">
		        	<div class="item-image-wrapper">
		        	<a href="<?php echo JRoute::_(CatalogueHelperRoute::getCategoryRoute($item)); ?>">
		        		<img src="<?php echo $img; ?>"/>
		        		<div class="item-link-wrapper">
		                	<span class="item-link"><?php echo $item->title; ?></span>
		        		</div>
		        	</a>
		        	</div>
		            <div class="item-desc-wrapper">
		            	<?php echo $item->description; ?>
		            </div>
	            	<div class="item-price-wrapper">
		            	<p>
		            		<span class="price-name">Цена: </span>
		            		<span class="item-price"><?php if($item->min_price && $item->max_price){ echo 'от '.number_format($item->min_price, 0, '.', ' ').' до '.number_format($item->max_price, 0, '.', ' ').' '.$params->get('catalogue_currency'); } else{ echo "по запросу"; } ?></span>
		            	</p>
	            	</div>
		        </div>
		<?php endforeach; ?>
	</div>
</div>