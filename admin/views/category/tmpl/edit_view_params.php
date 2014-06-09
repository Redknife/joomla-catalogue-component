<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_catalogue
 *
 * @copyright   Copyright (C) 2012 - 2014 Saity74 LLC, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//echo JLayoutHelper::render('joomla.edit.metadata', $this);
?>

<div class="row-fluid">
	<div class="span12">
		<?php $fieldSets = $this->form->getFieldsets(); 
			foreach($fieldSets as $fieldset){
				if ($fieldset->name == 'view_options'){

					foreach($fieldset as $field){
						var_dump($field);
					}
				}
			}
		?>
	</div>
</div>