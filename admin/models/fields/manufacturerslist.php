<?php

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/catalogue.php';


class JFormFieldManufacturersList extends JFormFieldList
{
	
	protected $type = 'ManufacturersList';

	
	public function getOptions()
	{
		return CatalogueHelper::getManufacturersOptions();
	}
}
