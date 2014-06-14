<?php

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/catalogue.php';


class JFormFieldSectionsList extends JFormFieldList
{
	
	protected $type = 'SectionsList';

	
	public function getOptions()
	{
		return CatalogueHelper::getSectionsOptions();
	}
}
