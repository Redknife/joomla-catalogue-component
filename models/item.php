<?php


defined('_JEXEC') or die;


class CatalogueModelItem extends JModelList
{
	
	public $_context = 'com_catalogue.item';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;

	
	protected function populateState()
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
			->select('title')
			->from('#__catalogue_categories')
			->where('state = 1 AND published AND id = '.$catid)
		);
		
		$this->setState('category.name', $db->loadResult());
		
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

	}
	
	public function getItem()
	{
		$id = JRequest::getVar('id', 0);
		
		$this->_addToWatchList($id);
		
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('c.*, cat.title AS category');
		$query->from('#__catalogue_items AS c');
		$query->join('LEFT', '#__catalogue_categories AS cat ON c.cat_id = cat.id');

		$query->where('c.state = 1 AND c.id = '.$id);
		
		$db->setQuery($query);
		$this->_items = $db->loadObject();
		
		$this->setState('item.name', $this->_items->name);
		
		return $this->_items;
	}
	
	public function getMore()
	{
		$id = JRequest::getVar('id');

		$cat_id = $this->_items->cat_id;
		
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('c.*');
		$query->from('#__catalogue_items AS c');
		$query->where('c.state = 1 AND c.cat_id = '.$cat_id);
		$query->where('NOT c.id = '.$id);
		
		
		$db->setQuery($query, 0, 4);
		$res = $db->loadObjectList();
		
		return $res;
	}
	
	protected function _addToWatchList($id)
	{
		$app = JFactory::getApplication();

		$watchlist = $app->getUserState('com_catalogue.watchlist');
		if (!CatalogueHelper::inWatchList($id))
		{
			
			$data = (array) unserialize($watchlist);
			
			
			if (!is_array($data))
			{
				$data = array();
			}
			array_unshift($data, $id);
			$data = array_unique($data);
			//$data = array_filter($data);
			
			if (count($data) > 8)
				$data = array_slice($data, 0, 9);
			
			$list = serialize($data);
			$app->setUserState('com_catalogue.watchlist', $list);
			
			return true;
		}
		
		return false;
	}
}