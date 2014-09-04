<?php defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

$model = JModelLegacy::getInstance('Order', 'CatalogueModel', array('ignore_request' => true));
$form = $model->getForm();
?>
<div class="order-form" id="orderFormWrapper">
	<div class="order-bg">
		<fieldset>
			<legend>Оформление заказа</legend>
				<form id="orderForm" class="form-validate" action="index.php" method="post">
					<div class="row">
						<div class="span4">
							<div class="control-group inline">
								<?php echo $form->getLabel('name'); ?>
								<?php echo $form->getInput('name'); ?>
								
							</div>
							<div class="control-group inline">
								<?php echo $form->getLabel('email'); ?>
								<?php echo $form->getInput('email'); ?><span class="divider"></span>
								<?php echo $form->getLabel('phone'); ?>
								<?php echo $form->getInput('phone'); ?>
							</div>
							<div class="control-group inline">
								<textarea class="span4" rows="4" placeholder="Текст сообщения"></textarea>
							</div>
						</div>
						<div class="span3">
							<div class="mark"></div>
							<div class="control-group">
								<?php echo $form->getLabel('name_to'); ?>
								<?php echo $form->getInput('name_to'); ?>
								<?php echo $form->getLabel('address_to'); ?>
								<?php echo $form->getInput('address_to'); ?>
								<div class="input-append">
									<?php echo $form->getLabel('date_to'); ?>
									<?php echo $form->getInput('date_to'); ?><a class="btn date-piker-btn"><i class="icon-calendar"></i></a>
								</div>
								
								<?php echo $form->getLabel('phone_to'); ?>
								<?php echo $form->getInput('phone_to'); ?>
							</div>
						</div>
						<div class="buttons align-center">
							<a id="submitOrderForm" class="btn btn-warning">Отправить</a>
						</div>
					</div>
					<input type="hidden" name="option" value="com_catalogue" />
					<input type="hidden" name="task" value="order.save" />
					<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
					<?php echo JHtml::_('form.token'); ?>
				</form>
		</fieldset>
	</div>
</div>