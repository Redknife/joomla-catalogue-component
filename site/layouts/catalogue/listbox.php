<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_catalogue
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication('Site');
$jform = $app->getUserState('com_catalogue.category.filter.jform');

$filter_name = 'fl_' . $displayData->filter_type . '_' . $displayData->id;
$filter_value = $jform['filter'][$filter_name];

?>
<div class="filter-box">
    <h4 class="filter-label"><?php echo $displayData->dir_name; ?></h4>

    <div class="filter_wrap">
        <select class="advancedSelect"
                name="jform[filter][fl_<?php echo $displayData->filter_type; ?>_<?php echo $displayData->id; ?>]">
            <?php if ($displayData->reset_attr_name) : ?>
                <option value="0"><?php echo $displayData->reset_attr_name; ?></option>
            <?php endif; ?>
            <?php foreach ($displayData->data as $filterItem) : ?>
                <?php $selected = ($filter_value == $displayData->filter_field . '_' . $filterItem->id) ? 'selected' : '' ?>
                <option
                    value="<?php echo $displayData->filter_field; ?>_<?php echo $filterItem->id; ?>" <?php echo $selected ?>><?php echo $filterItem->attr_name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

</div>
