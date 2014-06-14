<?php

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/catalogue.php';


class JFormFieldCategoriesList extends JFormFieldList
{
	
	protected $type = 'CategoriesList';

	
	public function getOptions()
	{
		return CatalogueHelper::getCategoriesOptions();
	}
}
