<?php

defined('_JEXEC') or die;

class CatalogueTableFields extends JTable
{

	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_field', 'id', $_db);
	}
	
}