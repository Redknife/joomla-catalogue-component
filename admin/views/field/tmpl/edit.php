<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'field.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_catalogue&view=field&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate form-vertical">
<div class="span8 form-vertical">

	<fieldset>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('COM_CATALOGUE_FIELD_DETAILS');?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="details">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('field_name'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('field_name'); ?>
					</div>
				</div>
				
                                <div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('field_label'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('field_label'); ?>
					</div>
				</div>
                                
                                <div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('form_id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('form_id'); ?>
					</div>
				</div>
                                
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('field_value'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('field_value'); ?>
					</div>
				</div>
                                
                                <div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('field_placeholder'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('field_placeholder'); ?>
					</div>
				</div>

                                <div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('field_required'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('field_required'); ?>
					</div>
				</div>
                                 
                                <div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('field_validate'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('field_validate'); ?>
					</div>
				</div>
                                 
                <div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('field_regexp'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('field_regexp'); ?>
					</div>
				</div>
				<div class="clearfix">
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
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
