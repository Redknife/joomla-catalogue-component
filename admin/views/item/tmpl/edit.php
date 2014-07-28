<?php
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$categoryId = $app->getUserStateFromRequest('com_catalogue.catalogue.filter.cat_id', 'filter_cat_id', '');

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'item.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			<?php echo $this->form->getField('item_description')->save(); ?>
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

	//window.addEvent('domready', function(){
	//	$('paramsTableAdd').addEvent('click', function(e){
	//		e.stop();
	//		var tr = new Element('tr');
	//		var remove = new Element('a').set({
	//			class: "btn btn-danger btn-small",
	//			events: {
	//				click : function(e){
	//					e.stop();
	//					this.getParent('tr').dispose();
	//				}
	//			}
	//		}).appendText('Удалить');
	//
	//		tr.innerHTML = '<td class="span2"><input type="text" class="inputBox required" name="jform[params][name][]" /></td><td class="span1"><input type="text" class="inputBox required" name="jform[params][count][]" /></td><td class="span1"><input type="text" class="inputBox required" name="jform[params][price][]" /></td><td class="span2"></td>';
	//		tr.getElement('td:last-child').adopt(remove);
	//		$('paramsTable').adopt(tr);
	//	})
	//
	//	$$('.paramsTableRemove').each(function(el){
	//		el.addEvent('click', function(e){
	//			e.stop();
	//			this.getParent('tr').dispose();
	//		})
	//	})
	//
	//})

	window.addEvent('domready', function(){

		$("assocTable").addEvent("click:relay(input['type=checkbox'])", function(event,node){

			var published = node.getParent('tr').getElement('.pub');
			published.set('value', node.get('checked') ? 1 : 0);
		});

		$('assocTableAdd').addEvent('click', function(e){
			e.stop();

			var tr = new Element('tr');
			var remove = new Element('a').set({
				class: "btn btn-danger btn-small",
				events: {
					click : function(e){
						e.stop();
						this.getParent('tr').dispose();
					}
				}
			}).appendText('Удалить');

			var assoc_id = $('jform_item_id').get('value'),
			assoc_name = $('jform_item_id').getSelected().get("text");
			if ($$('#row_'+assoc_id).length){alert('Такой товар уже есть'); return false;};

			tr.innerHTML = '<td width="1%" id="row_'+assoc_id+'"><input type="checkbox" checked="checked" /></td>\
							<input type="hidden" name="jform[assoc][assoc_id][]" value="'+assoc_id+'"/>\
							<input type="hidden" name="jform[assoc][ordering][]" value="'+$$('#assocTable tr').length+'"/>\
							<input type="hidden" name="jform[assoc][published][]" value="1" class="pub"/>\
							<td>'+assoc_name+'</td>\
							<td class="span2"></td>';
			tr.getElement('td:last-child').adopt(remove);
			$('assocTable').adopt(tr);
		})

		$$('.assocTableRemove').each(function(el){
			el.addEvent('click', function(e){
				e.stop();
				this.getParent('tr').dispose();
			});
		});
	})

</script>

<form action="<?php echo JRoute::_('index.php?option=com_catalogue&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate form-vertical">
<div class="span12 form-vertical">
<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_CATALOGUE_ITEM_DETAILS', true)); ?>
				<div class="span5">
					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('item_name'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('item_name'); ?>
								</div>
							</div>
						</div>

						<div class="span5">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('alias'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('alias'); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('item_art'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('item_art'); ?>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('item_count'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('item_count'); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('category_id'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('category_id'); ?>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('item_shortname'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('item_shortname'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('price'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('price'); ?>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('warranty'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('warranty'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('sticker'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('sticker'); ?>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('stock'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('stock'); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('price_list'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('price_list'); ?>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('assembly'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('assembly'); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('tech_desc'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('tech_desc'); ?>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('price_cat'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('price_cat'); ?>
								</div>
							</div>
						</div>
						<div class="span7">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('print_totalsum'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('print_totalsum'); ?>
								</div>
							</div>
						</div>
					</div>

					<div class="row-fluid">
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
								<?php echo $this->form->getLabel('id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('id'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="span7">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('item_description'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_description'); ?>
						</div>
					</div>
				</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_CATALOGUE_IMAGES', true)); ?>
			<div class="span12">
				<div class="span2">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('item_image'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_2'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_3'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_4'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_5'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_6'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_7'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_8'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_9'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_10'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_11'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_12'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_13'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_14'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_15'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_16'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_17'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_18'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_19'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_20'); ?>
						</div>
					</div>
				</div>
				<div class="span3">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('item_image_desc'); ?>
						</div>

						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_2'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_3'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_4'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_5'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_6'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_7'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_8'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_9'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_10'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_11'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_12'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_13'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_14'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_15'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_16'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_17'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_18'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_19'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_image_desc_20'); ?>
						</div>
					</div>
				</div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'assoc', JText::_('COM_CATALOGUE_ASSOC', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="controls">
				<div class="span4">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('item_id'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('item_id'); ?>
							<a href="#" id="assocTableAdd" class="btn btn-success">Добавить</a>
						</div>
					</div>

				</div>
			</div>

			<table class="table table-stripped tablesorter" id="assocTable">
				<thead>
					<tr>
						<th>#</th>
						<th>Название</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($this->item->assoc as $assoc) : ?>
					<tr class="roww" id="row_<?php echo $assoc->assoc_id; ?>">
						<td width="1%"><input type="checkbox" <?php if($assoc->published): ?> checked="checked" <?php endif; ?>/></td>
						<input type="hidden" name="jform[assoc][assoc_id][]" value="<?php echo $assoc->assoc_id; ?>"/>
						<input type="hidden" name="jform[assoc][ordering][]" value="<?php echo $assoc->ordering; ?>"/>
						<input type="hidden" name="jform[assoc][published][]" value="<?php echo $assoc->published; ?>" class="pub"/>
						<td><?php echo $assoc->assoc_name; ?></td>
						<td class="span2"><a class="btn btn-danger btn-small assocTableRemove">Удалить</a></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'adddesc', JText::_('COM_CATALOGUE_ADDDESC', true)); ?>
		<div class="row-fluid">
			<div class="span10">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('benefits'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('benefits'); ?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('color_scheme'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('color_scheme'); ?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('assets'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('assets'); ?>
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
