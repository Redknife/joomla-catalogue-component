<?php

defined('_JEXEC') or die;

class CatalogueModelManufacturers extends JModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'm.id',
				'manufacturer_name', 'm.manufacturer_name',
				'country_name', 'ct.country_name',
				'manufacturer_id','m.manufacturer_id',
				'country_id', 'm.country_id',
				'published', 'm.published',
				'ordering', 'm.ordering'
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
				'm.*'
			)
		);
		$query->from($db->quoteName('#__catalogue_manufacturer').' AS m');
	
		$query->select('ct.country_name');
		$query->join('LEFT', '#__catalogue_country AS ct ON ct.id = m.country_id');
		
		// Filter by country.
		$countryId = $this->getState('filter.country_id');
		if (is_numeric($countryId)) {
			$query->where('m.country_id = '.(int) $countryId);
		}
		
		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('m.state = '.(int) $published);
		} elseif ($published === '') {
			$query->where('(m.state IN (0, 1))');
		}
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('m.published = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(m.published = 0 OR m.published = 1)');
		}
		
		// Filter by country_id
		$country_id = $this->getState('filter.country_id');
		if ($country_id) {
			$query->where('m.country_id = ' . (int) $country_id);
		}
		
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('m.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(m.manufacturer_name LIKE '.$search.' OR m.alias LIKE '.$search.')');
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
		$id	.= ':'.$this->getState('filter.manufacturer_id');
		$id	.= ':'.$this->getState('filter.country_id');
		
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
		
		$country_id = $this->getUserStateFromRequest($this->context.'.filter.country_id', 'filter_country_id', '', 'string');
		$this->setState('filter.country_id', $country_id);

		$manufacturerId = $this->getUserStateFromRequest($this->context.'.filter.m_id', 'filter_m_id', '');
		$this->setState('filter.m_id', $manufacturerId);
		
		$id = $this->getUserStateFromRequest($this->context.'.manufacturer.id', 'id', 0, 'int');
		$this->setState('manufacturer.id', $id);

		$params = JComponentHelper::getParams('com_catalogue');
		$this->setState('params', $params);

		parent::populateState('m.manufacturer_name', 'asc');
	}
}
