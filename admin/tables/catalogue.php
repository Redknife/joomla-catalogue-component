<?php

defined('_JEXEC') or die;

class CatalogueTableCatalogue extends JTable
{
	
	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_item', 'id', $_db);
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
		
		$_db = JFactory::getDBo();
		
		$query = $_db->getQuery(true);
			
		$query->select('ss.id AS ssid, s.id AS sid, c.id AS cid');
		$query->from('#__catalogue_category AS c');
		$query->join('LEFT', '#__catalogue_section AS s ON s.id = c.section_id');
		$query->join('LEFT', '#__catalogue_supersection AS ss ON ss.id = s.supersection_id');
		$query->where('c.id = '.$array['category_id']);
		
		$_db->setQuery($query);
		$row = $_db->loadObject();
		
		$array['path'] = 'option=com_catalogue&view=item&ssid='.$row->ssid.'&sid='.$row->sid.'&cid='.$row->cid;
			
		return parent::bind($array, $ignore);
	}
	
	public function store($updateNulls = false)
	{
		$result = parent::store($updateNulls);
		
		return $result;
	}
	
}