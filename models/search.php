<?php
defined('_JEXEC') or die;

class CatalogueModelSearch extends JModelList
{
	
	public $_context = 'com_catalogue.search';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'itm.id',
				'item_name', 'itm.item_name',
				'alias', 'itm.alias',
				'state', 'itm.state',
				'ordering', 'itm.ordering',
				'published', 'itm.published'
			);
		}
		parent::__construct($config);
	}

    protected function populateState()
	{
      $app = JFactory::getApplication('site');
      $search = $app->getUserStateFromRequest($this->context.'.filters.search', 'search');
	  $this->setState('filter.search', $search);
      
      parent::populateState();
    }

	public function getItems()
	{
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
        $search = $this->getState('filter.search');
        $search = $db->Quote('%'.$db->escape($search, true).'%');
        
        $query->select('item_name, item_art, id');
		$query->from('#__catalogue_item');
		$query->where('(item_name LIKE '.$search.' OR item_art LIKE '.$search.') AND state = 1 AND published = 1');
		$db->setQuery($query);
		$this->_items = $db->loadObjectList();

		return $this->_items;
	}

}