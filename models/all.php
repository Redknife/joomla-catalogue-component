<?php
defined('_JEXEC') or die;

class CatalogueModelAll extends JModelList
{
	
	public $_context = 'com_catalogue.all';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;
	
	public function getListQuery()
	{
		// $this->setState('list.limit', 9);
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		

		$query->select('itm.*, sec.id as sectionid, cat.id as categoryid');
		$query->from('#__catalogue_item AS itm');
		$query->join('LEFT', '#__catalogue_category as cat ON cat.id = itm.category_id');
		$query->join('LEFT', '#__catalogue_section as sec ON sec.id = cat.section_id');
		$query->where('itm.state = 1  && itm.published = 1');
		$query->order('itm.ordering');

		$db->setQuery($query);
		
		return $query;
	}

	public function getHot()
	{
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('i.*, sec.id as sectionid, cat.id as categoryid');
		$query->from('#__catalogue_item AS i');
		$query->join('LEFT', '#__catalogue_category as cat ON cat.id = i.category_id');
		$query->join('LEFT', '#__catalogue_section as sec ON sec.id = cat.section_id');
		$query->where('i.state = 1  AND i.published = 1');
		$query->where('i.sticker = 2');
		
		$query->order('i.ordering');
		
		
		$db->setQuery($query, 0, 4);

		$result = $db->loadObjectList();

		return $result;
	}

	public function getNew()
	{
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('i.*, sec.id as sectionid, cat.id as categoryid');
		$query->from('#__catalogue_item AS i');
		$query->join('LEFT', '#__catalogue_category as cat ON cat.id = i.category_id');
		$query->join('LEFT', '#__catalogue_section as sec ON sec.id = cat.section_id');
		$query->where('i.state = 1  AND i.published = 1');
		$query->where('i.sticker = 1');
		
		$query->order('i.ordering');
		
		
		$db->setQuery($query, 0, 4);

		$result = $db->loadObjectList();

		return $result;
	}

	// protected function populateState($ordering = null, $direction = null)
	// {
	// 	$app = 	JFactory::getApplication();	
	// 	// $limit = $this->getUserStateFromRequest('filter.list.limit', 'limit', 9, 'int');
	// 	// $this->setState('filter.list.limit', $limit);
	// 	// parent::populateState($ordering, $direction);

	// 	$app = 	JFactory::getApplication();	
	// 	$limit = 9; //$app->getUserStateFromRequest('global.list.limit', 'limit', 9, 'int');
 //    $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
 //    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

 //    $this->setState('list.limit', $limit);
 //    $this->setState('list.limitstart', $limitstart);

	// }
}