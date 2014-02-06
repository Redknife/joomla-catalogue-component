<?php


defined('_JEXEC') or die;


class CatalogueModelCategory extends JModelList
{
	
	public $_context = 'com_catalogue.category';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;


	public function getListQuery()
	{
		// $this->setState('list.limit', 9);
		$catid = JRequest::getVar('cid', 0);
		$secid = JRequest::getVar('sid', 0);

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('itm.*, cat.category_name, sec.section_name');
		$query->from('#__catalogue_item AS itm');
		$query->join('LEFT', '#__catalogue_section as sec ON sec.id ='.$secid);
		$query->where('itm.state = 1  && itm.published = 1 && itm.category_id = '.$catid);
		$query->order('itm.ordering');

		$query->join('LEFT', '#__catalogue_category AS cat ON cat.id = '.$catid);

		$db->setQuery($query);
		
		return $query;
	}
}