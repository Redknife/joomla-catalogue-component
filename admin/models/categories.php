<?php

defined('_JEXEC') or die;

class CatalogueModelCategories extends JModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'cat.id',
				'category_name','cat.category_name',
				'section_name', 'sec.section_name',
				'supersection_name', 'ss.supersection_name',
				'section_id', 'cat.section_id',
				'supersection_id', 'sec.supersection_id',
				'published', 'cat.published',
				'ordering', 'cat.ordering'
			);
		}
		
		parent::__construct($config);
	}

	protected function getListQuery()
	{
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'cat.*'
			)
		);
		$query->from($db->quoteName('#__catalogue_category').' AS cat');
		
		$query->select('sec.section_name');
		$query->join('LEFT', '#__catalogue_section AS sec ON sec.id = cat.section_id');
		
		$query->select('ss.supersection_name');
		$query->join('LEFT', '#__catalogue_supersection AS ss ON ss.id = sec.supersection_id');
		
		// Filter by section.
		$sectionId = $this->getState('filter.section_id');
		if (is_numeric($sectionId)) {
			$query->where('cat.section_id = '.(int) $sectionId);
		}
		
		// Filter by supersection.
		$supersectionId = $this->getState('filter.supersection_id');
		if (is_numeric($supersectionId)) {
			$query->where('sec.supersection_id = '.(int) $supersectionId);
		}
		
		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('cat.state = '.(int) $published);
		} elseif ($published === '') {
			$query->where('(cat.state IN (0, 1))');
		}
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('cat.published = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(cat.published = 0 OR cat.published = 1)');
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('cat.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(cat.category_name LIKE '.$search.' OR cat.alias LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'ordering');
		$orderDirn	= $this->state->get('list.direction', 'ASC');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}


	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.category_id');
		$id	.= ':'.$this->getState('filter.section_id');
		$id	.= ':'.$this->getState('filter.supersection_id');
		
		return parent::getStoreId($id);
	}


	public function getTable($type = 'Catalogue', $prefix = 'CatalogueTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);
		
		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $published);

		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.cat_id', 'filter_cat_id', '');
		$this->setState('filter.cat_id', $categoryId);
		
		$sectionId = $this->getUserStateFromRequest($this->context.'.filter.section_id', 'filter_section_id', '');
		$this->setState('filter.section_id', $sectionId);
		
		$supersectionId = $this->getUserStateFromRequest($this->context.'.filter.supersection_id', 'filter_supersection_id', '');
		$this->setState('filter.supersection_id', $supersectionId);
		
		$id = $this->getUserStateFromRequest($this->context.'.categories.id', 'id', 0, 'int');
		$this->setState('categories.id', $id);
		
		$params = JComponentHelper::getParams('com_catalogue');
		$this->setState('params', $params);

		parent::populateState('cat.category_name', 'asc');
	}
}
