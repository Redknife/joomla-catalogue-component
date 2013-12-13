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
		if (task == 'category.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_catalogue&view=category&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate form-vertical">
<div class="span8 form-vertical">

	<fieldset>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('COM_CATALOGUE_CATEGORY_DETAILS');?></a></li>
		</ul>
		<div class="tab-content row-fluid">
			<div class="tab-pane active span6" id="details">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('category_name'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('category_name'); ?>
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
						<?php echo $this->form->getLabel('category_sale'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('category_sale'); ?>
					</div>
				</div>				
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('section_id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('section_id'); ?>
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
						<?php echo $this->form->getLabel('category_image'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('category_image'); ?>
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
			<div class="span6">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('child_item'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('child_item'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('child_category'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('child_category'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('child_section'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('child_section'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('child_supersection'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('child_supersection'); ?>
					</div>
				</div>
			</div>
			
			<div class="clearfix">
			</div>
			<div class="span12">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('category_description'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('category_description'); ?>
					</div>
				</div>
			</div>
		</div>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
