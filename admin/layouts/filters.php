<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$data = $displayData;

// Load the form filters
$filters = $data['view']->filterForm->getGroup('filter');
?>
<hr class="hr-condensed">
    
<div class="filter-select hidden-phone">
    <h4 class="page-header"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></h4>
    <?php if ($filters) : ?>
            <?php foreach ($filters as $fieldName => $field) : ?>
                <?php echo $field->input; ?>
                <?php echo '<hr class="hr-condensed">'; ?>
            <?php endforeach; ?>
    <?php endif; ?>
</div>