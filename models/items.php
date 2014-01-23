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

    protected function populateState()
	{
      $app = JFactory::getApplication('site');
      $catid = JRequest::getVar('cid', 0);
      $app->setUserState('category.id', $catid);
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