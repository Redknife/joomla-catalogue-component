<?php

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/catalogue.php';


class JFormFieldCountriesList extends JFormFieldList
{
	
	protected $type = 'CountriesList';

	
	public function getOptions()
	{
		return CatalogueHelper::getCountriesOptions();
	}
}
