<?php


defined('_JEXEC') or die;


class CatalogueModelCategory extends JModelList
{
	
	public $_context = 'com_catalogue.categories';

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
	
	public function getItems()
	{
		$catid = JRequest::getVar('cid', 0);
		
		$db	= JFactory::getDbo();
		$db->setQuery(
			$db->getQuery(true)
				->select('linked_id')
				->from('#__catalogue_categories')
				->where('id='.(int) $catid)
			     );
		
		$registry = new JRegistry();
		$registry->loadString($db->loadResult());
		$liked_id = $registry->toArray();
		
		$query	= $db->getQuery(true);

		$query->select('c.*');
		$query->from('#__catalogue_items AS c');
		if (is_array($liked_id) && !empty($liked_id))
			$query->where('(c.state = 1 AND c.published = 1 AND c.id IN ('.implode(',',$liked_id).'))', 'OR');
		$query->where('(c.state = 1 AND c.published = 1 AND c.cat_id = '.$catid.')');
		
		$query->order('c.ordering');
		
		$db->setQuery($query);

		$this->_items = $db->loadObjectList();
		
				
		return $this->_items;
	}
}