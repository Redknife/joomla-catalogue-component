<?php defined('_JEXEC') or die;
JHtml::_('behavior.caption');
$config = JFactory::getConfig();
// Загрузка глобальных параметров каталога
$params = $this->state->get('params');

// $catalague_order_type = $params->get('catalague_order_type', 1);
// $addprice = $params->get('addprice', 0);
// $slice_desc = $params->get('slice_desc', 0);
// $slice_len = $params->get('short_desc_len', 150);
// $img_width = $params->get('img_width', 250);
// $img_height = $params->get('img_height', 250);

$item = $this->item;

$app = JFactory::getApplication();
// $path = $app->getPathway();

// $path->addItem($this->state->get('item.name'));

// $mypath = $path->getPathway();
// $mypath['0']->name = 'Каталог';
// $mypath['0']->link = '#';
// $path->setPathway($mypath);

$doc = JFactory::getDocument();
$similar_items = $app->getUserState('com_catalogue.similaritems');
$similars = unserialize($similar_items);
//Технические характеристики
// $item_params = new JRegistry($item->params);
// $item->params = $item_params->toArray();
$images_arr = [['img' => $item->item_image, 'img_desc' => $item->item_image_desc],
				['img' => $item->item_image_2, 'img_desc' => $item->item_image_desc_2],
				['img' => $item->item_image_3, 'img_desc' => $item->item_image_desc_3],
				['img' => $item->item_image_4, 'img_desc' => $item->item_image_desc_4],
				['img' => $item->item_image_5, 'img_desc' => $item->item_image_desc_5],
				['img' => $item->item_image_6, 'img_desc' => $item->item_image_desc_6],
				['img' => $item->item_image_7, 'img_desc' => $item->item_image_desc_7],
				['img' => $item->item_image_8, 'img_desc' => $item->item_image_desc_8],
				['img' => $item->item_image_9, 'img_desc' => $item->item_image_desc_9],
				['img' => $item->item_image_10, 'img_desc' => $item->item_image_desc_10],
				['img' => $item->item_image_11, 'img_desc' => $item->item_image_desc_11],
				['img' => $item->item_image_12, 'img_desc' => $item->item_image_desc_12],
				['img' => $item->item_image_13, 'img_desc' => $item->item_image_desc_13],
				['img' => $item->item_image_14, 'img_desc' => $item->item_image_desc_14],
				['img' => $item->item_image_15, 'img_desc' => $item->item_image_desc_15],
				['img' => $item->item_image_16, 'img_desc' => $item->item_image_desc_16],
				['img' => $item->item_image_17, 'img_desc' => $item->item_image_desc_17],
				['img' => $item->item_image_18, 'img_desc' => $item->item_image_desc_18],
				['img' => $item->item_image_19, 'img_desc' => $item->item_image_desc_19],
				['img' => $item->item_image_20, 'img_desc' => $item->item_image_desc_20]];
$images = array_filter($images_arr, function($el){return $el['img'];});

// var_dump($item);
?>
<div id="item-open">
	<div class="page-header">
		<h2 class="item-name"><?php echo $item->item_name; ?></h2>
	</div>
	<div class="row">
		<section class="item-open-infos">
			<div class="item-image span5">
				<div class="item-slider-wrap">
					<a href="#" class="slider-prev slider-arrow"></a>
					<ul id="slider-container" class="unstyled item-slider-container">
					<?php foreach ($images as $cur_img): ?>
						<li>
							<a <?php if ($cur_img['img_desc']){ echo 'title="'.$cur_img['img_desc'].'"'; } ?> href="<?php echo $cur_img['img']; ?>">
								<img src="<?php $imgpath = CatalogueHelper::createThumb($item->id, $cur_img['img'], 380, 315, 'mid'); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.jpg'; }  ?>"/>
							</a>
						</li>
					<?php endforeach; ?>
					</ul>
					<a href="#" class="slider-next slider-arrow"></a>
				</div>
			</div>
			<div class="span4">
				<div class="info">
					<p class="price-name">Стоимость:</p>
					<p class="item-price"><?php if($item->price){ echo 'от '.number_format($item->price, 0, '.', ' ').' '.$params->get('catalogue_currency', 'руб.'); } else{ echo 'по запросу'; } ?></p>
					<div class="item-stock">
						<span><?php echo ($item->stock ? 'В наличии' : 'Нет в наличии' ); ?></span>
					</div>
					<div class="table-info">
						<span class="gray-left">Срок доставки:</span>
						<span class="black-right"><?php if($item->item_count){ echo $item->item_count; } else{ echo $item->delivery_period; } ?></span>
					</div>
					<div class="table-info">
						<span class="gray-left">Гарантия:</span>
						<span class="black-right"><?php echo $item->warranty; ?></span>
					</div>
					<div class="table-info">
						<span class="gray-left">Сборка:</span>
						<span class="black-right"><?php echo $item->assembly; ?></span>
					</div>
					<div class="graybox">
						<p>Позвоните, чтобы согласовать комплектацию, условия доставки и другие вопросы по продукции:</p>
						<p class="manager-phone"><?php echo $params->get('manager_phone'); ?></p>
					</div>
				</div>
			</div>
		</section>
		<section class="item-open-infos">
			<div class="span9">
				<?php if($item->price_list): ?>
				<div class="item-price-list">
					<a href="<?php echo $item->price_list; ?>" target="_blank">Прайс лист на всю серию</a>
				</div>
				<?php endif; ?>
				<?php if($item->tech_desc): ?>
				<div class="item-tech-desc">
					<a href="<?php echo $item->tech_desc; ?>" target="_blank">Техническое описание для тендера</a>
				</div>
				<?php endif; ?>
				<a href="#orderModal" role="button" class="red-btn item-open-order-btn" data-toggle="modal"><?php echo $params->get('cart_form_btn', 'Сделайте заказ'); ?></a>
			</div>
		</section>
		<section class="item-open-infos item-desc-tabs">
			<div class="span9">
				<ul id="itemTabs" class="unstyled item-open-tabs">
					<?php $active = 0; ?>
					<?php if(!empty($item->assoc)): ?>
						<?php $active = 1; ?>
						<li class="active"><a href="#assoc" data-toggle="tab"><?php if($item->print_totalsum){ echo 'Типовая компоновка';} else{ echo 'Вся серия';} ?></a></li>
					<?php endif; ?>
					<?php if(!empty($item->item_description)): ?>
						<li <?php if($active == 0){ echo 'class="active"'; $active=1;} ?> ><a href="#desc" data-toggle="tab">Описание</a></li>
					<?php endif; ?>
					<?php if(!empty($item->benefits)): ?>
						<li <?php if($active == 0){ echo 'class="active"'; $active=1;} ?> ><a href="#benefits" data-toggle="tab">Преимущества серии</a></li>
					<?php endif; ?>
					<?php if(!empty($item->color_scheme)): ?>
						<li <?php if($active == 0){ echo 'class="active"'; $active=1;} ?> ><a href="#colors" data-toggle="tab">Цветовые решения</a></li>
					<?php endif; ?>
					<?php if(!empty($item->assets)): ?>
						<li <?php if($active == 0){ echo 'class="active"'; $active=1;} ?> ><a href="#assets" data-toggle="tab">Дизайнерам и архитекторам</a></li>
					<?php endif; ?>
				</ul>
				<div class="tab-content">
					<?php $active = 0; ?>
					<?php if(!empty($item->assoc)): ?>
					<?php $active = 1; ?>
						<div id="assoc" class="tab-pane active">
							<ul class="unstyled">
								<?php $total_sum = 0; foreach ($item->assoc as $assoc_item): ?>
								<li class="one-assoc-item">
									<div class="assoc-item-img">
										<?php $assoc_imgpath = CatalogueHelper::createThumb($assoc_item->id, $assoc_item->item_image, 90, 90, 'small'); if($assoc_imgpath): ?>
										<img src="<?php echo $assoc_imgpath; ?>" alt="">
									<?php else: ?>
										<span>Изображение отсутствует</span>
									<?php endif; ?>
								</div>
								<div class="assoc-item-info">
									<p class="assoc-item-name">Элемент: <?php echo $assoc_item->item_name; ?></p>
									<p class="assoc-item-count">Количество: <?php echo $assoc_item->item_count; ?></p>
									<p class="assoc-item-art">Артикул: <?php echo $assoc_item->item_art; ?></p>
									<p class="assoc-item-price">Цена от: <?php echo number_format($assoc_item->price, 0, '.', ' ').' '.$params->get('catalogue_currency', 'руб.'); ?></p>
								</div>
							</li>
							<?php $total_sum = $total_sum+$assoc_item->price; ?>
						<?php endforeach; ?>

						<?php if($item->print_totalsum): ?>
						<div class="violet-bordered">
							<p class="total-sum-name">Итого за комплект:</p>
							<p class="total-sum"><?php echo number_format($total_sum, 0, '.', ' ').' '.$params->get('catalogue_currency', 'руб.'); ?></p>
						</div>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>
			<?php if(!empty($item->item_description)): ?>
				<div id="desc" class="tab-pane <?php if($active == 0){ echo 'active'; $active=1;} ?>">
					<?php echo $item->item_description; ?>
				</div>
			<?php endif; ?>
			<?php if(!empty($item->benefits)): ?>
				<div id="benefits" class="tab-pane <?php if($active == 0){ echo 'active'; $active=1;} ?>">
					<?php echo $item->benefits; ?>
				</div>
			<?php endif; ?>
			<?php if(!empty($item->color_scheme)): ?>
				<div id="colors" class="tab-pane <?php if($active == 0){ echo 'active'; $active=1;} ?>">
					<?php echo $item->color_scheme; ?>
				</div>
			<?php endif; ?>
			<?php if(!empty($item->assets)): ?>
				<div id="assets" class="tab-pane <?php if($active == 0){ echo 'active'; $active=1;} ?>">
					<?php echo $item->assets; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<section>
	<div class="span9">
		<h3>Товары в аналогичной ценовой категории:</h3>
		<div class="row">
			<?php foreach ($similars as $similar): ?>
				<?php
				$ilink = JRoute::_(CatalogueHelperRoute::getItemRoute($similar['id'], $similar['category_id']) );
				$src	= CatalogueHelper::createThumb($similar['id'], $similar['item_image'], 220, 174, 'min');
				if (!$src) $src = '/templates/blank_j3/img/imgholder.jpg';
				?>
				<div class="span3">
					<div class="catalogue-item" itemscope="" itemtype="http://schema.org/Product">
						<a href="<?php echo $ilink; ?>" title="<?php echo $similar['item_name']; ?>">
							<img src="<?php echo $src ?>" title="<?php echo $similar['item_name']; ?>" alt="<?php echo $similar['item_name']; ?>" width="220px" height="174px" style="width: 220px;height: 174px" itemprop="image" />
						</a>
						<h5 itemprop="name">
							<a class="product-name" href="<?php echo $ilink; ?>" title="<?php echo $similar['item_name']; ?>" itemprop="url"><?php echo $similar['item_name']; ?></a>
						</h5>
						<p>
							<span class="price-name">Цена: </span>
							<span class="item-price"  itemprop="offers" itemscope="" itemtype="http://schema.org/Offer"><?php echo 'от '.number_format($similar['price'], 0, '.', ' ').' '.$params->get('catalogue_currency', 'руб.'); ?></span>
							<meta itemprop="priceCurrency" content="0">
						</p>
						<p>
							<span class="price-name">Срок доставки: </span>
							<span class="item-price"><?php echo $similar['item_count']; ?></span>
						</p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
</div>
</div>
<!-- Modal -->
<div id="orderModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
	<div class="modal-header">
        <button class="close" data-dismiss="modal"></button>
    </div>
	<div class="modal-body">
		<div id="modalItem">
		<h1><?php echo $params->get('cart_form_header'); ?></h1>
			<div class="item-image-wrap">
				<img src="<?php echo $item->item_image; ?>" width="490px" />
				</div
				><div class="item-form-wrap">
				<form action="<?php echo JRoute::_( 'index.php?option=com_catalogue' ); ?>" method="POST" id="orderForm" class="mail-form form-validate">
					<div class="form-lables">
						<div class="controlls">
							<input type="text" placeholder="Ваше имя" class="field required" value="" name="name" />
						</div>
						<div class="controlls">
							<input type="text" placeholder="Ваш E-mail" class="field required" value="" name="email" />
						</div>
						<div class="controlls">
							<input type="text" placeholder="Ваш телефон" class="field required" value="" name="phone" />
						</div>
						<div class="controlls form-desc">
							<textarea placeholder="Опишите задачу:" class="field" name="desc" ></textarea>
						</div>
					</div>
					<div>
						<a href="#" class="red-btn" id="orderSubmit"><?php echo $params->get('cart_form_btn'); ?></a>
					</div>
					<?php echo JHtml::_( 'form.token' ); ?>
					<input type="hidden" name="id" value="<?php echo $item->id; ?>">
					<input type="hidden" name="task" value="cart.send">
				</form>
			</div>
		</div>
	</div>
</div>