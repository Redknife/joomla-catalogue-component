<?php

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'section.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_catalogue&view=section&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate form-vertical">
<div class="span8 form-vertical">

	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_CATALOGUE_SECTION_DETAILS', true)); ?>
		<div class="row-fluid">
			<div class="span6">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('section_name'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('section_name'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('alias'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('alias'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('state'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('state'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('supersection_id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('supersection_id'); ?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('section_sale'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('section_sale'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('section_image'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('section_image'); ?>
					</div>
				</div>
			</div>

			<div class="span12">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('section_description'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('section_description'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('id'); ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'metadata', JText::_('COM_CATALOGUE_METADATA', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	
		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>
		
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>		


	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
