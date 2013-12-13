<?php

defined('_JEXEC') or die;

class CatalogueTableItemEffectLinks extends JTable
{

	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_item_effect', 'id', $_db);
	}
	
}