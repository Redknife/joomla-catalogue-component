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
		
		if (isset($array['item_description'])) {
            $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
            $tagPos    = preg_match($pattern, $array['item_description']);
 
            if ($tagPos == 0) {
                $this->introtext    = $array['item_description'];
                $this->fulltext         = '';
            } else {
                list($this->introtext, $this->fulltext) = preg_split($pattern, $array['item_description'], 2);
            }
        }
        
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
		
		if (is_array($this->params))
		{
			$registry = new JRegistry;
			$registry->loadArray($this->params);
			$this->params = (string) $registry;
		}

		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		
		if ($this->id)
		{
			// Existing item
			$this->modified		= $date->toSql();
			$this->modified_by	= $user->get('id');
		}
		else
		{
			// New contact. A contact created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
		}
		
		return parent::store($updateNulls);
	}
	
}