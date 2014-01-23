<?php
defined('_JEXEC') or die;
JHtml::_('behavior.caption');
JHtml::_('bootstrap.tooltip');
$i = 0;
$tmpsec = 0;
?>

<div class="catalogue-items" id="catalogue">
	<div class="page-header">
		<h1 class="catalogue-head"><?php echo $this->items[0]->category_name; ?> </h1>
		<p class="ben-desc">
			Для заказа, пожалуйста, проставьте требуемое количество изделий около каждого необходимого наименования и нажмите "В корзину".</br>
			Цены на всю продукцию только по запросу
		</p>
	</div>
		<table class="table-items">
			<thead>
				<tr>
					<th>Наименование</th>
					<th>Обозначение</th>
					<th>Кол-во</th>
					<th>Заказать</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->items as $item){ ?>
				<tr>
					<td class="item-name">
						<?php echo $item->item_name; ?>
					</td>
					<td class="item-art">
						<?php echo $item->item_art ?>
					</td>
					<td>
							<div class="item-count">
								<input value="<?php echo $gc = CatalogueHelper::getCount($item->id); ?>" class="input-count"/>
								<div class="count-controls">
									<a href="#" class="count-arrowup"></a>
									<a href="#" class="count-arrowdown"></a>
								</div>
							</div>
					</td>
					<td>
                      <?php if (!CatalogueHelper::inCart($item->id)) : ?>
                        <a href="#" id="b<?php echo $item->id; ?>" data-id="<?php echo $item->id; ?>" class="addToCart">В корзину</a></td>
                      <?php else : ?>
                        <a id="b<?php echo $item->id; ?>" data-id="<?php echo $item->id; ?>" class="addToCart inCart">В корзине</a>
                      <?php endif; ?>
                    </td>  
				</tr>
			<?php } ?>
			</tbody>
		</table>
	<div class="working-company">
		<h1 class="catalogue-head">Преимущества сотрудничества с нашей компанией</h1>
		<p class="ben-desc">
			Основным видом деятельности компании является производство и реализация запчастей для тепловозов 2ТЭ10, 2ТЭ116, М62 и их различных модификаций. Мы предлагаем на продажу только высококачественные и надежные запчасти, которые обеспечат длительную эксплуатацию вашего тепловоза и повысят его функциональные качества.
		</p>
		<h3>Какие запчасти мы предлагаем?</h3>
		<p class="ben-desc">
			Мы быстро развиваемся, расширяем объемы производства и ассортимент запасных частей. На сегодняшний день компания осуществляет комплексные услуги по поставке основных механизмов и узлов к тепловозам 2ТЭ10, 2ТЭ116, М62, изготовленных как на нашем предприятии, так и сторонними производителями.
		</p>
	</div>
 </div>