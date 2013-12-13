<?php defined('_JEXEC') or die; 

JHtml::_('behavior.caption');
JHtml::_('behavior.formvalidation');

$items = CatalogueHelper::getCartItems();
$summ = 0;
?>

<ul class="breadcrumb">
	<li><i class="icon-home"></i></li>
	<li><a href="" title="Каталог">Каталог</a></li>
	<li><i class="icon-chevron-right"></i></li>
	<li>Корзина</li>
</ul>

<div class="cart-items">
<div class="alert alert-info">
	<p>Впишите количество товаров в таблице ниже. Чтобы удалить товар нажмите на крестик справа.</p>
</div>
<form action="cart.html" method="POST" class="form-validate">
	<table id="cart-table" class="table table-condensed">
		<thead>
			<tr>
				<th>#</th>
				<th>Изображение</th>
				<th>Название</th>
				<th>Описание</th>
				<th>Цена</th>
				<th>Количество</th>
				<th>Стоимость</th>
			</tr>
		<thead>
		<?php if ($items) : foreach ($items as $item) : $summ += $item->price; ?>
			<tr id="t<?php echo $item->id?>" class="cart-row">
				<td width="1%"><?php echo $i += 1 ?></td>
				<td><img class="thumbnail span1" src="<?php echo $item->image ?>"/></td>
				<td class="span2"><?php echo $item->name ?></td>
				<td style="font-size: 0.9em"><?php echo JFilterOutput::cleanText($item->desc) ?></td>
				<td class="span1"><span class="row-price badge badge-warning"><?php echo $item->price ?> р.</span></td>
				<td class="span1"><input type="text" name="cart[<?php echo $item->id?>][count]" class="count required input-mini" value="1" aria-required="true" required="required" /></td>
				<td class="span1"><span class="row-summ"><?php echo $item->price ?></span> р.<button class="close">×</button></td>				
			</tr>
		<?php endforeach; endif; ?>
	</table>
		<div style="text-align: center; font-size: 2em">
			Сумма Вашего заказа: <span id="cart-summ"><?php echo $summ ?></span> р.
		</div>
</div>
<div class="form-actions form-horizontal">
	<div class="alert alert-info">
		<p>Обращаем Ваше внимание на то, что при оформлении заказа все поля обязательны для заполнения. Не верно или не корректно заполненные заказы выполняться не будут!</p>
	</div>
	
	<div class="control-group">
	<label class="control-label" for="name">Фамилия имя отчество:</label>
		<div class="controls">
		  <input type="text" name="name" class="span4 required" id="name" aria-required="true" required="required">
		  <p class="help-block"><small>Ведите Фамилию Имя и Отчество заказчика</small></p>
		</div>
	</div>
	<div class="control-group">
	<label class="control-label" for="address">Адрес доставки:</label>
		<div class="controls">
		  <input type="text" name="address" class="span4 required" id="address" aria-required="true" required="required">
		  <p class="help-block"><small>Адрес: Например г. Копейск, ул Калинина 13-46</small></p>
		</div>
	</div>
	<div class="control-group">
	<label class="control-label" for="phone">Телефон:</label>
		<div class="controls">
		  <input type="text" name="phone" class="span4 required" id="phone" aria-required="true" required="required">
		  <p class="help-block"><small>Телефон по которому можно с Вами связаться</small></p>
		</div>
	</div>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="task" value="send" />
	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<button type="submit" class="btn btn-primary">Оформить заказ</button>
		</div>
	</div>
</div>
</form>