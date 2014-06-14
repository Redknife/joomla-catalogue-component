<?php

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/catalogue.php';


class JFormFieldSupersectionsList extends JFormFieldList
{
	
	protected $type = 'SupersectionsList';

	
	public function getOptions()
	{
		return CatalogueHelper::getSupersectionsOptions();
	}
}
