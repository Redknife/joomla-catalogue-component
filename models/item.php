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
		$query->select('itm.*, cat.category_name, sec.section_name');
		$query->from('#__catalogue_item AS itm');
		$query->join('LEFT', '#__catalogue_category AS cat ON cat.id = itm.category_id');
		$query->where('itm.state = 1  && itm.published = 1 && itm.id='.$id);

		$query->join('LEFT', '#__catalogue_section AS sec ON sec.id = cat.section_id');

		$db->setQuery($query);
		$this->_items = $db->loadObject();
		
		$this->setState('item.name', $this->_items->item_name);
		
		return $this->_items;
	}
	
	public function getMore()
	{
		$id = JRequest::getVar('id');
        $amountspace = 10;
        $depthfirebox = 50;
        $width = 50;
        $height = 50;
        $length = 50;
        $diameter = 10;

		$db	= JFactory::getDbo();
 		$query	= $db->getQuery(true);
		
			//Определение категории
			$query->select('i.*');
			$query->from('#__catalogue_item AS i');
			$query->where('i.id = '.$id);
			$db->setQuery($query);
			$this_item = $db->loadObject();

			$query_more = $db->getQuery(true);
			$query_more->select('i.*, sec.id as sectionid, cat.id as categoryid');
			$query_more->from('#__catalogue_item AS i');
			$query_more->join('LEFT', '#__catalogue_category as cat ON cat.id = i.category_id');
			$query_more->join('LEFT', '#__catalogue_section as sec ON sec.id = cat.section_id');
			$query_more->where('i.state = 1');
			$query_more->where('i.published = 1');
			$query_more->where('i.id <> '.$id);
			$query_more->where('i.item_amountspace <= '.(int)($this_item->item_amountspace + $amountspace));
			$query_more->where('i.item_amountspace >= '.(int)($this_item->item_amountspace-$amountspace));
			$query_more->where('i.item_depthfirebox <= '.(int)($this_item->item_depthfirebox+$depthfirebox));
			$query_more->where('i.item_depthfirebox >= '.(int)($this_item->item_depthfirebox-$depthfirebox));
			$query_more->where('i.item_diameter <= '.(int)($this_item->item_diameter+$diameter));
			$query_more->where('i.item_diameter >= '.(int)($this_item->item_diameter-$diameter));
			$query_more->where('i.item_width <= '.(int)($this_item->item_width+$width));
			$query_more->where('i.item_width >= '.(int)($this_item->item_width-$width));
			$query_more->where('i.item_height <= '.(int)($this_item->item_height+$height));
			$query_more->where('i.item_height >= '.(int)($this_item->item_height-$height));
			$query_more->where('i.item_length <= '.(int)($this_item->item_length+$length));
			$query_more->where('i.item_length >= '.(int)($this_item->item_length-$length));
			
			$query_more->order('i.ordering');
			$db->setQuery($query_more, 0, 4);
		
		$items_arr = $db->loadObjectList();
		return $items_arr;
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