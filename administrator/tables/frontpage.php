<?php

defined('_JEXEC') or die;

class CatalogueTableFrontpage extends JTable
{
	
	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_frontpage', 'id', $_db);
	}

}