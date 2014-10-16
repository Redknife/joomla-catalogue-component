<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_catalogue
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT . '/helpers/filter.php');
?>

<div class="catalogue-form-wrapper">
    <div id="catalogue_filter_hint" class="hidden">
        <a href="#">Показать результаты</a>
    </div>
    <?php

    foreach ($this->filters as $filter) {
        $filter->data = CatalogueFilterHelper::getFilterData($filter);
        echo JLayoutHelper::render('catalogue.' . $filter->filter_type, $filter, JPATH_COMPONENT . 'layouts');
    }

    ?>
    <div class="submit-container">
        <input type="submit" value="Найти"/>
    </div>
</div>