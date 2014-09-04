<?php

defined('_JEXEC') or die;

class CatalogueTableOrder extends JTable
{

	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_orders', 'id', $_db);
	}
	
}