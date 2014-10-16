<?php

defined('_JEXEC') or die;

class CatalogueModelAttrDirs extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'd.id',
                'ordering', 'd.ordering',
                'dir_name', 'd.attr_name',
            );
        }

        parent::__construct($config);
    }

    public function getTable($type = 'AttrDir', $prefix = 'CatalogueTable', $config = array())
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
                'd.*'
            )
        );
        $query->from($db->quoteName('#__catalogue_attrdir') . ' AS d');

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('d.id = ' . (int)substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('(d.dir_name LIKE ' . $search . ')');
            }
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'ordering');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        if ($orderCol == 'ordering' || $orderCol == 'dir_name') {
            $orderCol = 'd.dir_name ' . $orderDirn . ', d.ordering';
        }

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

        $id = $this->getUserStateFromRequest($this->context . '.attrdir.id', 'id', 0, 'int');
        $this->setState('attrdir.id', $id);

        $params = JComponentHelper::getParams('com_catalogue');
        $this->setState('params', $params);

        parent::populateState('d.dir_name', 'asc');
    }
}
