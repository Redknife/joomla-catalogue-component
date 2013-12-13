<?php

defined('_JEXEC') or die;

class CatalogueModelSupersections extends JModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'ssec.id',
				'supersection_name', 'ssec.supersection_name',
				'published', 'ssec.published',
				'ordering', 'ssec.ordering'
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
				'ssec.*'
			)
		);
		$query->from($db->quoteName('#__catalogue_supersection').' AS ssec');

		// Filter by published state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
			$query->where('ssec.state = '.(int) $state);
		} elseif ($state === '') {
			$query->where('(ssec.state IN (0, 1))');
		}
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('ssec.published = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(ssec.published = 0 OR ssec.published = 1)');
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('ssec.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(ssec.supersection_name LIKE '.$search.' OR ssec.alias LIKE '.$search.')');
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
		$id	.= ':'.$this->getState('filter.supersection_id');

		return parent::getStoreId($id);
	}


	public function getTable($type = 'Supersections', $prefix = 'CatalogueTable', $config = array())
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
		
		$id = $this->getUserStateFromRequest($this->context.'.supersection.id', 'id', 0, 'int');
		$this->setState('supersection.id', $id);

		$params = JComponentHelper::getParams('com_catalogue');
		$this->setState('params', $params);

		parent::populateState('ssec.supersection_name', 'asc');
	}
}
