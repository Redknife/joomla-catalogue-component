<?php

defined('_JEXEC') or die;

class CatalogueTableSupersections extends JTable
{

	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_supersection', 'id', $_db);
	}
	
	public function bind($array, $ignore = '')
	{
		
		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}
			
		return parent::bind($array, $ignore);
	}

	
}