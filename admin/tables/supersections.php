<?php

defined('_JEXEC') or die;

class CatalogueTableSupersections extends JTable
{

	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_supersection', 'id', $_db);
	}
	
}