<?php

defined('_JEXEC') or die;

class CatalogueModelFields extends JModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'fl.id',
				'filed_name', 'fl.filed_name',
				'published', 'fl.published',
				'ordering', 'fl.ordering',
				'form', 'fm.form_name',
				'form_id', 'fl.form_id'
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
				'fl.*'
			)
		);
		$query->from($db->quoteName('#__catalogue_field').' AS fl');

		$query->select('fm.form_name');
		$query->join('LEFT', '#__catalogue_form AS fm ON fm.id = fl.form_id');
		
		// Filter by form.
		$formId = $this->getState('filter.form_id');
		if (is_numeric($formId)) {
			$query->where('fl.form_id = '.(int) $formId);
		}

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('fl.state = '.(int) $published);
		} elseif ($published === '') {
			$query->where('(fl.state IN (0, 1))');
		}
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('fl.published = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(fl.published = 0 OR fl.published = 1)');
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('fl.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(fl.form_name LIKE '.$search.')');
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
		$id	.= ':'.$this->getState('filter.field_id');
		$id	.= ':'.$this->getState('filter.form_id');

		return parent::getStoreId($id);
	}


	public function getTable($type = 'Fields', $prefix = 'CatalogueTable', $config = array())
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
		
		$id = $this->getUserStateFromRequest($this->context.'.field.id', 'id', 0, 'int');
		$this->setState('field.id', $id);
		
		$form_id = $this->getUserStateFromRequest($this->context.'.filter.form_id', 'filter_form_id', '', 'string');
		$this->setState('filter.form_id', $form_id);
		
		$params = JComponentHelper::getParams('com_catalogue');
		$this->setState('params', $params);

		parent::populateState('fl.field_name', 'asc');
	}
}
