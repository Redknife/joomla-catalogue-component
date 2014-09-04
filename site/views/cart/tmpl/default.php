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
					<th>
						Наименование
					</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($items as $item): ?>
				<tr>
					<td class="item-name">
						<?php echo $item->item_name; ?>
					</td>
					<td><a href="index.php?option=com_catalogue&task=cart.remove_from_cart&id=<?php echo $item->id; ?>" title="Удалить товар из корзины" class="close">x</a></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
	</table>

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
</div>