<?php


defined('_JEXEC') or die;


class CatalogueModelItem extends JModelList
{
	
	public $_context = 'com_catalogue.item';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;

	public function getItem()
	{
		$id = JRequest::getVar('id', 0);
		
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('itm.*, cat.title AS category_name');
		$query->from('#__catalogue_item AS itm');
		$query->join('LEFT', '#__categories AS cat ON cat.id = itm.category_id');
		$query->where('itm.state = 1  && itm.published = 1 && itm.id='.$id);

		$db->setQuery($query);
		$this->_items = $db->loadObject();
		
		$this->_addToWatchList($this->_items->id);
		
		return $this->_items;
	}
	
	protected function populateState($ordering = NULL, $direction = NULL)
	{
		$app = JFactory::getApplication('site');

		$offset = $app->input->getUInt('limitstart');
		$this->setState('list.offset', $offset);
		
		$id = $app->input->getUInt('id');
		$this->setState('item.id', $id);
		
		$db	= JFactory::getDbo();
		
		$db->setQuery(
			$db->getQuery(true)
			->select('item_name, item_description, params, metadata')
			->from('#__catalogue_item')
			->where('state = 1 AND published AND id = '.$id)
		);
		
		$item = $db->loadObject();
		
		$this->setState('item.name', $item->item_name);
		$this->setState('item.params', $item->params);
		$this->setState('item.metadata', $item->metadata);
		$this->setState('item.desc', $item->item_description);
		
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

	}
	
	private function _addToWatchList($id)
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