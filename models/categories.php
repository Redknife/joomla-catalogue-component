<?php


defined('_JEXEC') or die;


class CatalogueModelCategories extends JModelList
{
	
	public $_context = 'com_catalogue.categories';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;

	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'cat.id',
				'title', 'cat.title',
				'alias', 'cat.alias',
				'state', 'cat.state',
				'ordering', 'cat.ordering',
				'published', 'cat.published',
				'image', 'cat.images'
			);
		}

		parent::__construct($config);
	}

	
	public function getItems()
	{
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('cat.*, c.image as subimage');
		$query->from('#__catalogue_categories AS cat');
		$query->select('COUNT(c.id) as count');
		$query->join('LEFT', '#__catalogue_items as c ON c.cat_id = cat.id');
		$query->where('cat.state = 1 AND cat.published = 1');
		$query->group('cat.id');
		$query->order('cat.ordering');

		$db->setQuery($query);

		$this->_items = $db->loadObjectList();
		
		return $this->_items;
	}
}