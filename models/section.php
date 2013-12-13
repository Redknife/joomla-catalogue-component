<?php


defined('_JEXEC') or die;


class CatalogueModelSection extends JModelList
{
	
	public $_context = 'com_catalogue.section';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'sec.id',
				'title', 'sec.title',
				'alias', 'sec.alias',
				'state', 'sec.state',
				'ordering', 'sec.ordering',
				'published', 'sec.published'
			);
		}
                
                $this->jinput = JFactory::getApplication()->input;
                $this->sid = $this->jinput->getInt('sid');
		parent::__construct($config);
	}

	
	public function getItems()
	{
                
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('c.*, cat.title as category, cat.desc as category_desc, sec.title as section');
		$query->from('#__catalogue_items AS c');
		$query->join('LEFT', '#__catalogue_categories as cat ON c.cat_id = cat.id');
                $query->join('LEFT', '#__catalogue_sections as sec ON cat.sec_id = sec.id');
		$query->where('cat.state = 1 AND cat.published = 1');
		$query->order('cat.ordering, c.ordering');
                $query->where('sec.id = '.(int) $this->sid);
		$db->setQuery($query);
		$this->_items = $db->loadObjectList();

		return $this->_items;
	}
	
}