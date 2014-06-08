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
		$catid = $this->getState('category.id', 0);
		$secid = $this->getState('section.id', 0);

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
	
	
	protected function populateState($ordering = NULL, $direction = NULL)
	{
		$app = JFactory::getApplication('site');

		$ssection_id = $app->getUserStateFromRequest($this->context.'.ssid', 'cid', 0, 'int');
		$this->setState('supersection.id', $ssection_id);
		
		$section_id = $app->getUserStateFromRequest($this->context.'.sid', 'sid', 0, 'int');
		$this->setState('section.id', $section_id);
		
		$category_id = $app->getUserStateFromRequest($this->context.'.cid', 'cid', 0, 'int');
		$this->setState('category.id', $category_id);
		
		$id = $app->input->getUInt('id');
		$this->setState('item.id', $id);
		
		$db	= JFactory::getDbo();
		
		$db->setQuery(
			$db->getQuery(true)
			->select('supersection_name, supersection_description')
			->from('#__catalogue_supersection')
			->where('state = 1 AND published AND id = '.$ssection_id)
		);
		
		$supersection = $db->loadObject();
		
		$this->setState('supersection.name', $supersection->supersection_name);
		$this->setState('supersection.desc', $supersection->supersection_description);
	
		
		$db->setQuery(
			$db->getQuery(true)
			->select('section_name, section_description')
			->from('#__catalogue_section')
			->where('state = 1 AND published AND id = '.$section_id)
		);
		
		$section = $db->loadObject();
		
		$this->setState('section.name', $section->section_name);
		$this->setState('section.desc', $section->section_description);

		
		$db->setQuery(
			$db->getQuery(true)
			->select('category_name, category_description, params, metadata')
			->from('#__catalogue_category')
			->where('state = 1 AND published AND id = '.$category_id)
		);
		$category = $db->loadObject();
		
		$this->setState('category.name', $category->category_name);
		$this->setState('category.desc', $category->category_description);
		$this->setState('category.metadata', $category->metadata);
		$this->setState('category.params', $category->params);
		
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

	}
}