<?php
defined('_JEXEC') or die;
JHtml::_('behavior.caption');
JHtml::_('bootstrap.tooltip');
?>

<div class="catalogue-items" id="catalogue">
	<div class="page-header">
		<h1 class="catalogue-head">Результаты поиска</h1>
	</div>
        <?php if( count($this->items) ): ?>
        <p class="ben-desc">
			Для заказа, пожалуйста, проставьте требуемое количество изделий около каждого необходимого наименования и нажмите "В корзину".</br>
			Цены на всю продукцию только по запросу
		</p>
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
        <?php else: ?>
        <p>По вашему запросу ничего не найдено</p>
        <?php endif; ?>
 </div>