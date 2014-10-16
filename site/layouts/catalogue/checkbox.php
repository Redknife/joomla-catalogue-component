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

?>
    <div class="filter-box">
        <h4 class="filter-label"><?php echo $displayData->dir_name; ?></h4>
        <ul>
            <?php foreach ($displayData->data as $filterItem) : ?>
                <li class="filter_wrap">
                    <?php

                    $filter_value = 0;

                    if (!empty($jform) && isset($jform['filter'][$filterItem->input_name])) {
                        $filter_value = $jform['filter'][$filterItem->input_name];
                    }

                    $checked = $filter_value ? 'checked="checked"' : '';

                    ?>
                    <input type="checkbox" value="<?php echo $filter_value ?>" <?php echo $checked ?>
                           id="<?php echo $filterItem->input_name; ?>"
                           name="jform[filter][<?php echo $filterItem->input_name; ?>]"/>
                    <label for="<?php echo $filterItem->input_name; ?>"><?php echo $filterItem->attr_name; ?></label>

                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php ?>