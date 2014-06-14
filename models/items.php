<?php
defined('_JEXEC') or die;

class CatalogueModelItems extends JModelList
{
	
	public $_context = 'com_catalogue.items';

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

    protected function populateState($ordering = NULL, $direction = NULL)
	{

		$app = JFactory::getApplication('site');

		$offset = $app->input->getUInt('limitstart');
		$this->setState('list.offset', $offset);
		
		$catid = $app->input->getUInt('cid');
		$this->setState('filter.category_id', $catid);
		
		$id = $app->input->getUInt('id');
		$this->setState('item.id', $id);
		
		$db	= JFactory::getDbo();

		$db->setQuery(
			$db->getQuery(true)
			->select('title AS category_name, category_description')
			->from('#__categories')
			->where('state = 1 AND published AND id = '.$catid)
		);
		$category = $db->loadObject();
		
		$this->setState('category.name', $category->category_name);
		$this->setState('category.desc', $category->category_description);
		
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
		
		parent::populateState('ordering', 'ASC');
    }

	public function getItems()
	{
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);		
		
		$categoryId = $this->getState('filter.category_id');
		
		if (is_numeric($categoryId))
		{
			$type = $this->getState('filter.category_id.include', true) ? '= ' : '<> ';
			
			// Add subcategory check
			$includeSubcategories = $this->getState('filter.subcategories', false);
			$categoryEquals = 'itm.category_id ' . $type . (int) $categoryId;
			
			if ($includeSubcategories)
			{
				$levels = (int) $this->getState('filter.max_category_levels', '1');

				// Create a subquery for the subcategory list
				$subQuery = $db->getQuery(true)
					->select('sub.id')
					->from('#__categories as sub')
					->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt')
					->where('this.id = ' . (int) $categoryId);

				if ($levels >= 0)
				{
					$subQuery->where('sub.level <= this.level + ' . $levels);
				}

				// Add the subquery to the main query
				$query->where('(' . $categoryEquals . ' OR itm.category_id IN (' . $subQuery->__toString() . '))');
			}
			else
			{
				$query->where($categoryEquals);
			}
		}
		elseif (is_array($categoryId) && (count($categoryId) > 0))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);

			if (!empty($categoryId))
			{
				$type = $this->getState('filter.category_id.include', true) ? 'IN' : 'NOT IN';
				$query->where('itm.category_id ' . $type . ' (' . $categoryId . ')');
			}
		}
		
		
		
        $query->select('itm.*, cat.title AS category_name')
			->from('#__catalogue_item AS itm')
			->join('LEFT', '#__categories as cat ON itm.category_id = cat.id')
			->where('itm.state = 1 AND itm.published = 1');
			
		$db->setQuery($query);
		
		$this->_items = $db->loadObjectList();

		return $this->_items;
	}
  
  public function getItem($id){
   $db	= JFactory::getDbo();
   $query	= $db->getQuery(true);

   $query->select('itm.*');
   $query->from('#__catalogue_item AS itm');
   $query->where('itm.id = '.$id);
   $db->setQuery($query);
   $this->_items = $db->loadObject();

   return $this->_items;
  }

}