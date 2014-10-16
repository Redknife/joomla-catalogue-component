<?php

defined('_JEXEC') or die;

class CatalogueModelAttrs extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'ordering', 'a.ordering',
                'attr_name', 'a.attr_name',
                'attr_type', 'a.attr_type'
            );
        }

        parent::__construct($config);
    }

    public function getTable($type = 'Attr', $prefix = 'CatalogueTable', $config = array())
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
                'a.*'
            )
        );
        $query->from($db->quoteName('#__catalogue_attr') . ' AS a');


        // Filter by country_id
        $attrdir_id = $this->getState('filter.attrdir_id');
        if ($attrdir_id) {
            $query->where('a.attrdir_id = ' . (int)$attrdir_id);
        }


        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int)substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('(a.attr_name LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')');
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

        return parent::getStoreId($id);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication('administrator');

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);

        $id = $this->getUserStateFromRequest($this->context . '.attr.id', 'id', 0, 'int');
        $this->setState('attr.id', $id);

        $attrdir_id = $this->getUserStateFromRequest($this->context . '.filter.attrdir_id', 'filter_attrdir_id', 0, 'int');
        $this->setState('filter.attrdir_id', $attrdir_id);

        $params = JComponentHelper::getParams('com_catalogue');
        $this->setState('params', $params);

        parent::populateState('a.attr_name', 'asc');
    }
}
