<?php


defined('_JEXEC') or die;


class CatalogueModelAll extends JModelList
{
	
	public $_context = 'com_catalogue.all';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;


	public function getItems()
	{
		
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('c.*');
		$query->from('#__catalogue_items AS c');
		$query->where('c.state = 1  && c.published = 1');
		$query->order('c.ordering');

		$db->setQuery($query);

		$this->_items = $db->loadObjectList();
		
		return $this->_items;
	}
}