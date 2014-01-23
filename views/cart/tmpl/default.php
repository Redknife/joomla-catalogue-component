<?php defined('_JEXEC') or die; 

JHtml::_('behavior.caption');
JHtml::_('behavior.formvalidation');

$items = CatalogueHelper::getCartItems();
$summ = 0;
?>
<div class="cart-items">
	<div class="page-header">
		<h1 class="catalogue-head">Корзина</h1>
	</div>
    <?php if(!empty($items)): ?>
<form action="cart.html" method="POST" id="orderForm" class="form-validate">
<table id="catalogueOrderBody" class="table-items">
			<thead>
				<tr>
					<th>Наименование</th>
					<th>Обозначение</th>
					<th>Кол-во</th>
				</tr>
			</thead>
			<tbody>
			<?php
        if($items):
          foreach ($items as $item){
            if( $item != end($items)): ?>
				<tr id="t<?php echo $item->id; ?>" class="cart-row">
					<td class="item-name">
						<?php echo $item->item_name; ?>
					</td>
					<td class="item-art">
						<?php echo $item->item_art; ?>
					</td>
					<td>
						<div class="item-count">
								<input value="<?php echo ($items[count($items)-1][$item->id]); ?>" class="input-count"/>
								<div class="count-controls">
									<a href="#" class="count-arrowup"></a>
									<a href="#" class="count-arrowdown"></a>
								</div>
					    </div>
					</td>
					<td><a href="#" title="Удалить товар из корзины" class="close"></a></td>
				</tr>
			<?php endif; } endif;?>
			</tbody>
		</table>

	<div class="form-left-info">
      <h3>Запросить цены, условия и сроки поставки</h3>
      <p>! Наш менеджер перезвонит Вам в течении 1-го рабочего дня после отправки заявки</p>
    </div>

    <div class="mail-form">
      <div class="form-lables">
        <div class="controlls">
            <span>Ваше имя</span>
            <input type="text" class="field required" value="" name="name" placeholder="" />
        </div>

        <div class="controlls">
            <span>Email</span>
            <input type="text" class="field required" value="" name="email" placeholder="" />
        </div>

        <div class="controlls">
                <span>Телефон</span>
                <input type="text" class="field" value="" name="phone" placeholder="" />
        </div>

        <div class="controlls form-desc">
            <span>Описание</span>
            <textarea class="field" name="desc" placeholder="" ></textarea>
         </div>
      </div>
			<div class="controlls">
	      <a href="#" class="cart-submit btn btn-success" id="cartSubmit">Запросить</a>
	    </div>
    </div>
</div>
<?php echo JHtml::_( 'form.token' ); ?>
<input type="hidden" name="task" value="send">
</form>
<?php else: ?>
<p class="empty-cart">Корзина пуста</p>
<?php endif; ?>
