<?php defined('_JEXEC') or die; 
JHtml::_('behavior.caption');

$config = JFactory::getConfig();

$params = $this->state->get('params');
$item = $this->item;

$app = JFactory::getApplication();
$path = $app->getPathway();

$path->addItem($item->section_name);
$path->addItem($item->category_name);
$path->addItem($this->state->get('item.name'));

$mypath = $path->getPathway();
$mypath['0']->name = 'Каталог';
$mypath['0']->link = JRoute::_('index.php?option=com_catalogue&view=all&Itemid=103');
$path->setPathway($mypath);

$doc = JFactory::getDocument();
$doc->setTitle( $config->get('sitename').' - '.$item->item_name);
?>
<div class="row">
	<div id="item-open" class="catalogue-item span12">
		<div class="white-box">
			<div class="float-left">
				<img src="<?php $imgpath = CatalogueHelper::createThumb($item->id, $item->item_image, 235, 215, 'mid'); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.jpg'; }  ?>"/>
			</div>
			<div class="float-right card-item-desc">
				<h2 class="item-name"><?php echo $item->item_name; ?></h2>
				<p class="item-desc"><?php echo strip_tags($item->item_shortdesc); ?></p>
				<div class="price-wrap">
					<span class="item-price"><?php echo $item->price.' руб.'; ?></span>
	        <?php if (!CatalogueHelper::inCart($item->id)) : ?>
                        <a href="index.php?option=com_catalogue&task=order&orderid=<?php echo $item->id; ?>&Itemid=114&backuri=<?php echo base64_encode(JURI::current()); ?>" class="addToCart">Заказать</a></td>
                <?php else : ?>
                        <a id="b<?php echo $item->id; ?>" data-id="<?php echo $item->id; ?>" class="addToCart inCart">В корзине</a>
                <?php endif; ?>
				</div>
				<div class="tabs-wrap">
    			<ul class="nav nav-tabs">
		        <li class="active"><a href="#itDesc" data-toggle="tab">Описание</a></li>
		        <li><a href="#itTech" data-toggle="tab">Тех.Характеристики</a></li>
    			</ul>

				<div class="tab-content">
					<div id="itDesc" class="tab-pane active"><?php echo strip_tags($item->item_description); ?></div>
					<div id="itTech" class="tab-pane">
						<table>
							<tbody>
							<tr>
								<th>Объем отапливаемого помещения</th><td><?php echo 'до '.$item->item_amountspace.' м3'; ?></td>
							</tr>
							<tr>
								<th>Размеры ШхДхВ</th><td><?php echo $item->item_width.'x'.$item->item_length.'x'.$item->item_height.' мм'; ?></td>
							</tr>
							<tr>
								<th>Глубина топки</th><td><?php echo $item->item_depthfirebox.' мм'; ?></td>
							</tr>
							<tr>
								<th>Вес</th><td><?php echo $item->item_weight.' кг'; ?></td>
							</tr>
							<tr>
								<th>Диаметр дымохода</th><td><?php echo $item->item_diameter.' мм'; ?></td>
							</tr>
							<tr>
								<th>Цвет</th><td><?php echo $item->item_color; ?></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				</div>
			</div>

			<div class="clearfix"></div>
		</div>
	</div>
</div>
<?php if (!empty($this->more)) : ?>
<div class="row">
	<div class="span12">
	<h2>Похожие товары</h2>
	<div class="popular-item in-item">
    <ul сlass="unstyled">
		<?php foreach ($this->more as $more_item) : ?>
			<li class="one-item span3 white-box">
			<?php $ilink = JRoute::_( 'index.php?option=com_catalogue&view=item&sid='.$more_item->sectionid.'&cid='.$more_item->categoryid.'&id='.$more_item->id.'&Itemid=110' ); ?>
                <a href="<?php echo $ilink; ?>" class="item-link">
                        <img src="<?php $imgpath = CatalogueHelper::createThumb($more_item->id, $more_item->item_image, 218, 203); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.png'; }  ?>"/>
                        <p class="item-head"><?php echo $more_item->item_name; ?></p>
                </a>
                <div class="info">
                        <p class="item-desc">
                                <?php echo mb_substr(strip_tags($more_item->item_shortdesc),0,65).' ...'; ?>
                        </p>
                        <span class="item-price"><?php echo $more_item->price.' руб.'; ?></span>
                        <a href="#" id="b<?php echo $more_item->id ?>" data-id="<?php echo $more_item->id; ?>" class="addToCart"> Заказать </a>

                </div>
        </li>
		<?php endforeach; ?>
    </ul>
			</div>
	</div>
</div>
<?php endif; ?>