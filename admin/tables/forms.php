<?php

defined('_JEXEC') or die;

class CatalogueTableForms extends JTable
{

	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_form', 'id', $_db);
	}
	
}