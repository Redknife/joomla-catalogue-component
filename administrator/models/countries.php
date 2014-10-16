<?php

defined('_JEXEC') or die;

class CatalogueModelCountries extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'ctr.id',
                'country_name', 'ctr.country_name',
                'published', 'ctr.published',
                'ordering', 'ctr.ordering'
            );
        }

        parent::__construct($config);
    }

    public function getTable($type = 'Countries', $prefix = 'CatalogueTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'ctr.*'
            )
        );
        $query->from($db->quoteName('#__catalogue_country') . ' AS ctr');

        // Filter by published state
        $state = $this->getState('filter.state');
        if (is_numeric($state)) {
            $query->where('ctr.state = ' . (int)$state);
        } elseif ($state === '') {
            $query->where('(ctr.state IN (0, 1))');
        }

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('ctr.published = ' . (int)$published);
        } elseif ($published === '') {
            $query->where('(ctr.published = 0 OR ctr.published = 1)');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('ctr.id = ' . (int)substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('(ctr.country_name LIKE ' . $search . ' OR ctr.alias LIKE ' . $search . ')');
            }
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'ordering');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        //echo nl2br(str_replace('#__','jos_',$query));
        return $query;
    }

    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.ctr_id');

        return parent::getStoreId($id);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication('administrator');

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);

        $access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
        $this->setState('filter.access', $access);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $id = $this->getUserStateFromRequest($this->context . '.country.id', 'id', 0, 'int');
        $this->setState('country.id', $id);

        $params = JComponentHelper::getParams('com_catalogue');
        $this->setState('params', $params);

        parent::populateState('ctr.country_name', 'asc');
    }
}
