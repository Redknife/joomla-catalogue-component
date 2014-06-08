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
		$this->setState('category.id', $catid);
		
		$id = $app->input->getUInt('id');
		$this->setState('item.id', $id);
		
		$db	= JFactory::getDbo();

		$db->setQuery(
			$db->getQuery(true)
			->select('category_name, category_description')
			->from('#__catalogue_category')
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
		//$catid = JRequest::getVar('cid', 0);
        
        $app = JFactory::getApplication('site');
        $catid =  $app->input->get('cid');
		
        $query->select('itm.item_name, itm.item_art, itm.id, cat.category_name');
		$query->from('#__catalogue_item AS itm');
		$query->join('LEFT', '#__catalogue_category as cat ON cat.id = '.$catid);
		$query->where('itm.category_id = '.$catid.' AND itm.state = 1 AND itm.published = 1');
		$query->order('cat.category_name');
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