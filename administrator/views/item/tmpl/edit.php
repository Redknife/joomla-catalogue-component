<?php
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$categoryId = $app->getUserStateFromRequest('com_catalogue.catalogue.filter.cat_id', 'filter_cat_id', '');

$doc = JFactory::getDocument();
$doc->addScript('/administrator/components/com_catalogue/assets/js/tablednd.js');

?>

<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'item.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            <?php echo $this->form->getField('item_description')->save(); ?>
            Joomla.submitform(task, document.getElementById('item-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }

    jQuery(function ($) {
        $(document).ready(function () {
            $('#techsTable').tableDnD();
        });


    });

    window.addEvent('domready', function () {

        if($$(".attr-table").length){
            $$(".attr-table").addEvent("click:relay(input.attr_value)", function (event, node) {
                $(this).set('value', $(this).get('checked') ? 1 : -1)
            });
        }

        $("assocTable").addEvent("click:relay(input['type=checkbox'])", function (event, node) {

            var published = node.getParent('tr').getElement('.pub');
            published.set('value', node.get('checked') ? 1 : 0);
        });

        $('assocTableAdd').addEvent('click', function (e) {
            e.stop();

            var tr = new Element('tr');
            var remove = new Element('a').set({
                class: "btn btn-danger btn-small",
                events: {
                    click: function (e) {
                        e.stop();
                        this.getParent('tr').dispose();
                    }
                }
            }).appendText('Удалить');

            var assoc_id = $('jform_item_id').get('value'),
                assoc_name = $('jform_item_id').getSelected().get("text");
            if ($$('#assocTable #row_' + assoc_id).length) {
                alert('Такой товар уже есть');
                return false;
            }
            ;

            tr.innerHTML = '<td width="1%" id="row_' + assoc_id + '"><input type="checkbox" checked="checked" /></td>\
							<input type="hidden" name="jform[assoc][assoc_id][]" value="' + assoc_id + '"/>\
							<input type="hidden" name="jform[assoc][ordering][]" value="' + $$('#assocTable tr').length + '"/>\
							<input type="hidden" name="jform[assoc][published][]" value="1" class="pub"/>\
							<td>' + assoc_name + '</td>\
							<td class="span2"></td>';
            tr.getElement('td:last-child').adopt(remove);
            $('assocTable').adopt(tr);
        })

        $$('.assocTableRemove').each(function (el) {
            el.addEvent('click', function (e) {
                e.stop();
                this.getParent('tr').dispose();
            });
        });

        // reviews..

        $("reviewsTable").addEvent("click:relay(input['type=checkbox'])", function (event, node) {
            var published = node.getParent('tr').getElement('.pub');
            published.set('value', node.get('checked') ? 1 : 0);
        });

        $('reviewTableAdd').addEvent('click', function (e) {
            e.stop();

            var tr = new Element('tr');
            var remove = new Element('a').set({
                class: "btn btn-danger btn-small",
                events: {
                    click: function (e) {
                        e.stop();
                        this.getParent('tr').dispose();
                    }
                }
            }).appendText('Удалить');

            var review_fio = $('review_input_fio').get('value'),
                review_date = $('review_input_date').get('value'),
                review_rate = $('review_input_rate').get('value'),
                review_text = $('review_input_text').get('value');

            // if ($$('#reviewTable #review_row_'+assoc_id).length){alert('Такой товар уже есть'); return false;};

            tr.innerHTML = '<td width="1%"><input type="checkbox" /></td>\
							<input type="hidden" name="jform[reviews][ordering][]" value=""/>\
							<input type="hidden" name="jform[reviews][published][]" value="" class="pub"/>\
							<td><input type="text" name="jform[reviews][review_fio][]" value="' + review_fio + '"/></td>\
							<td><input type="text" name="jform[reviews][review_rate][]" value="' + review_rate + '"/></td>\
							<td><input type="text" name="jform[reviews][review_date][]" value="' + review_date + '"/></td>\
							<td><textarea name="jform[reviews][review_text][]" value="' + review_text + '">' + review_text + '</textarea></td>\
							<td class="span2"></td>';
            tr.getElement('td:last-child').adopt(remove);
            $('reviewsTable').adopt(tr);
        })

        $$('.reviewTableRemove').each(function (el) {
            el.addEvent('click', function (e) {
                e.stop();
                this.getParent('tr').dispose();
            });
        });

        // techs..
        $("techsTable").addEvent("click:relay(input['type=checkbox'])", function (event, node) {
            var published = node.getParent('tr').getElement('.pub');
            published.set('value', node.get('checked') ? 1 : 0);
        });

        $('techsTableAdd').addEvent('click', function (e) {
            e.stop();
            var tr = new Element('tr');
            var remove = new Element('a').set({
                class: "btn btn-danger btn-small",
                events: {
                    click: function (e) {
                        e.stop();
                        this.getParent('tr').dispose();
                    }
                }
            }).appendText('Удалить');

            tr.innerHTML = '<td width="1%"><input type="checkbox"/></td><input type="hidden" name="jform[techs][show_short][]" value="" class="pub"/><td class="span2"><input type="text" class="inputBox required" name="jform[techs][name][]" /></td><td class="span1"><input type="text" class="inputBox required" name="jform[techs][value][]" /></td><td class="span2"></td>';
            tr.getElement('td:last-child').adopt(remove);
            $('techsTable').adopt(tr);
        })

        $$('.techsTableRemove').each(function (el) {
            el.addEvent('click', function (e) {
                e.stop();
                this.getParent('tr').dispose();
            })
        });
    });

</script>

<form action="<?php echo JRoute::_('index.php?option=com_catalogue&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="item-form" class="form-validate form-vertical">
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

        <div class="span11">
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('manufacturer_id'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->form->getInput('manufacturer_id'); ?>
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

            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('item_sale'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->form->getInput('item_sale'); ?>
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

        <div class="control-label">
            <?php echo $this->form->getLabel('item_shortdesc'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('item_shortdesc'); ?>
        </div>

        <div class="control-label">
            <?php echo $this->form->getLabel('item_techdesc'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('item_techdesc'); ?>
        </div>
    </div>
</div>
<?php echo JHtml::_('bootstrap.endTab'); ?>

<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'techs', JText::_('COM_CATALOGUE_ITEMTECHS', true)); ?>
<div class="row-fluid form-horizontal-desktop">
    <h3>Технические характеристики</h3>
    <table id="techsTable" class="table table-stripped span12">
        <tr>
            <th>Показать в кратком</th>
            <th>Характеристика</th>
            <th>Значение</th>
            <th><a href="#" id="techsTableAdd" class="btn btn-success btn-small">Добавить</a></th>
        </tr>
        <?php foreach ($this->item->techs as $tech) : ?>
            <tr>
                <td width="1%"><input
                        type="checkbox" <?php if ($tech['show_short']): ?> checked="checked" <?php endif; ?>/></td>
                <input type="hidden" name="jform[techs][show_short][]" value="<?php echo $tech['show_short']; ?>"
                       class="pub"/>
                <td class="span2"><input type="text" class="inputBox required" name="jform[techs][name][]"
                                         value="<?php echo $tech['name'] ?>"/></td>
                <td class="span1"><input type="text" class="inputBox required" name="jform[techs][value][]"
                                         value="<?php echo $tech['value'] ?>"/></td>
                <td class="span2"><a href="#" class="techsTableRemove btn btn-danger btn-small">Удалить</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
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
        <?php foreach ($this->item->assoc as $assoc) : ?>
            <tr class="roww" id="row_<?php echo $assoc->assoc_id; ?>">
                <td width="1%"><input
                        type="checkbox" <?php if ($assoc->published): ?> checked="checked" <?php endif; ?>/></td>
                <input type="hidden" name="jform[assoc][assoc_id][]" value="<?php echo $assoc->assoc_id; ?>"/>
                <input type="hidden" name="jform[assoc][ordering][]" value="<?php echo $assoc->ordering; ?>"/>
                <input type="hidden" name="jform[assoc][published][]" value="<?php echo $assoc->published; ?>"
                       class="pub"/>
                <td><?php echo $assoc->assoc_name; ?></td>
                <td class="span2"><a class="btn btn-danger btn-small assocTableRemove">Удалить</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo JHtml::_('bootstrap.endTab'); ?>


<!-- REVIEWS.. -->
<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'reviews', JText::_('COM_CATALOGUE_REVIEWS', true)); ?>
<div class="row-fluid form-horizontal-desktop">
    <div class="controls">
        <div class="span12">
            <div class="control-group">
                <div class="control-label">
                    ФИО
                </div>
                <div class="controls">
                    <input id="review_input_fio" type="text" name="jform[_reviews_][review_fio][]" value=""/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    Рейтинг
                </div>
                <div class="controls">
                    <input id="review_input_rate" type="text" name="jform[_reviews_][review_rate][]" value=""/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    Дата
                </div>
                <div class="controls">
                    <!-- <input class="review_input_date" type="text" name="jform[_reviews_][review_date][]" value=""/> -->
                    <?php echo JHTML::_('calendar', $value = '' . date('Y-m-d') . '', $name = 'jform[_reviews_][review_date][]', $id = 'review_input_date', $format = '%Y-%m-%d', $attribs = array('size' => '8', 'maxlength' => '10', 'class' => ' validate[\'required\']',)); ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    Текст
                </div>
                <div class="controls">
                    <textarea id="review_input_text" name="jform[_reviews_][review_text][]"></textarea>
                    <a href="#" id="reviewTableAdd" class="btn btn-success">Добавить</a>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-stripped tablesorter" id="reviewsTable">
        <thead>
        <tr>
            <th>#</th>
            <th>ФИО</th>
            <th>Рейтинг</th>
            <th>Дата</th>
            <th>Текст</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->item->reviews as $review) : ?>
            <tr class="review_roww" id="review_row_<?php echo $review->id; ?>">
                <td width="1%"><input
                        type="checkbox" <?php if ($review->published): ?> checked="checked" <?php endif; ?>/></td>
                <input type="hidden" name="jform[reviews][ordering][]" value="<?php echo $review->ordering; ?>"/>
                <input type="hidden" name="jform[reviews][published][]" value="<?php echo $review->published; ?>"
                       class="pub"/>
                <td><input type="text" name="jform[reviews][review_fio][]"
                           value="<?php echo $review->item_review_fio; ?>"/></td>
                <td><input type="text" name="jform[reviews][review_rate][]"
                           value="<?php echo $review->item_review_rate; ?>"/></td>
                <td><input type="text" name="jform[reviews][review_date][]"
                           value="<?php echo $review->item_review_date; ?>"/></td>
                <td><textarea name="jform[reviews][review_text][]"
                              value="<?php echo $review->item_review_text; ?>"><?php echo $review->item_review_text; ?></textarea>
                </td>
                <td class="span2"><a class="btn btn-danger btn-small reviewTableRemove">Удалить</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo JHtml::_('bootstrap.endTab'); ?>
<!-- ..REVIEWS -->


<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'attrs', JText::_('COM_CATALOGUE_ATTRS', true)); ?>
<div class="row-fluid form-horizontal-desktop tabs-left">
    <?php

    echo JHtml::_('bootstrap.startTabSet', 'attrsTab', array('active' => 'tab_' . $this->item->attrdirs{0}->attrdir_id));
    echo JHtml::_('bootstrap.addTab', 'attrsTab', 'tab_' . $this->item->attrdirs{0}->attrdir_id, $this->item->attrdirs{0}->dir_name);
    echo '<table class="table table-stripped tablesorter attr-table"><thead><tr><th>Название фильтра</th><th>Значение</th><th>Цена</th><th>Изображение</th></tr></thead><tbody>';

    $current_dir = $this->item->attrdirs{0}->attrdir_id;

    foreach ($this->item->attrdirs as $attr_dir) {


        if ($current_dir != $attr_dir->attrdir_id) {
            $current_dir = $attr_dir->attrdir_id;

            echo '</tbody></table>';
            echo JHtml::_('bootstrap.endTab');
            echo JHtml::_('bootstrap.addTab', 'attrsTab', 'tab_' . $current_dir, $attr_dir->dir_name);
            echo '<table class="table table-stripped tablesorter attr-table"><thead><tr><th>Название фильтра</th><th>Значение</th><th>Цена</th><th>Изображение</th></tr></thead><tbody>';
        }

        echo '<tr class="roww" id="row_' . $attr_dir->attr_id . '">
					
					<input type="hidden" name="jform[params][attr_id][]" value="attr_' . $attr_dir->attr_id . '"/>
					
					<td>' . $attr_dir->attr_name . '</td>';

        switch ($attr_dir->attr_type) {
            case 'integer' :
                echo '<td><input type="input" class="inputbox attr_value" name="jform[params][attr][' . $attr_dir->attr_id . ']" value="' . $attr_dir->attr_value . '"/></td>';
                break;
            case 'string' :
                echo '<td><input type="input" class="inputbox attr_value" name="jform[params][attr][' . $attr_dir->attr_id . ']" value="' . $attr_dir->attr_value ? $attr_dir->attr_value : $attr_dir->attr_default . '"/></td>';
                break;
            case 'bool' :
                echo '<td><input type="checkbox" class="inputbox attr_value" name="jform[params][attr][' . $attr_dir->attr_id . ']" value="' . ($attr_dir->attr_value ? 1 : -1) . '" ' . ($attr_dir->attr_value == 1 ? 'checked="checked"' : '') . '/></td>';
                break;
        }

        $this->form->setValue('attr_image', null, $attr_dir->attr_image);
        $this->form->setFieldAttribute('attr_image', 'id', 'attr_image_' . $attr_dir->attr_id);

        echo '<td><input type="input" class="inputbox" name="jform[params][attr_price][]" value="' . $attr_dir->attr_price . '"/></td>
					<td>' . $this->form->getInput('attr_image') . '</td>
					
				</tr>';


    }

    echo '</tbody></table>';
    echo JHtml::_('bootstrap.endTab');
    echo JHtml::_('bootstrap.endTabSet');

    ?>
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

<input type="hidden" name="task" value=""/>
<?php echo JHtml::_('form.token'); ?>
</div>
</form>