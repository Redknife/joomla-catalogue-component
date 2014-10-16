<?php

defined('_JEXEC') or die;

class CatalogueModelFrontpage extends JModelList
{

    public function __construct($config = array())
    {


        parent::__construct($config);
    }

    public function saveorder($pks = null, $order = null)
    {
        // Initialise variables.
        $table = $this->getTable();
        $conditions = array();
        $user = JFactory::getUser();

        if (empty($pks)) {
            return JError::raiseWarning(500, JText::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
        }

        // update ordering values
        foreach ($pks as $i => $pk) {
            $table->load((int)$pk);

            // Access checks.
            if ($table->ordering != $order[$i]) {
                $table->ordering = $order[$i];

                if (!$table->store()) {
                    $this->setError($table->getError());
                    return false;
                }

                // remember to reorder within position and client_id
                $condition = $this->getReorderConditions($table);
                $found = false;

                foreach ($conditions as $cond) {
                    if ($cond[1] == $condition) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $key = $table->getKeyName();
                    $conditions[] = array($table->$key, $condition);
                }
            }
        }

        // Execute reorder for each category.
        foreach ($conditions as $cond) {
            $table->load($cond[0]);
            $table->reorder($cond[1]);
        }

        // Clear the component's cache
        $this->cleanCache();

        return true;
    }

    public function getTable($type = 'Frontpage', $prefix = 'CatalogueTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function getReorderConditions($table)
    {
        return array();
    }

    public function getItems()
    {
        $items = parent::getItems();
        $data = array();
        foreach ($items as $item) {
            $path_arr = explode('&', $item->path);
            $query = array();

            $path_arr = array_reverse($path_arr);

            $q = explode('=', $path_arr[0]);

            if (in_array($q[0], array('cid', 'sid', 'ssid', 'id'))) {
                $data[$q[0]][] = (int)$q[1];
                $item->{$q[0]} = (int)$q[1];
            }

        }
        $db = $this->getDbo();

        if (!empty($data['ssid'])) {
            $query = $db->getQuery(true);
            $query->select('id as ssid, supersection_name as title');
            $query->from('#__catalogue_supersection');
            $query->where('id IN (' . implode(',', $data['ssid']) . ')');

            $db->setQuery($query);
            $supersections = $db->loadAssocList('ssid');

            foreach ($items as $item) {
                if (isset($item->ssid))
                    $item->title = $supersections[$item->ssid]['title'];
            }
        }

        if (!empty($data['sid'])) {
            $query = $db->getQuery(true);
            $query->select('id as sid, section_name as title');
            $query->from('#__catalogue_section');
            $query->where('id IN (' . implode(',', $data['sid']) . ')');

            $db->setQuery($query);
            $sections = $db->loadAssocList('sid');

            foreach ($items as $item) {
                if (isset($item->sid))
                    $item->title = $sections[$item->sid]['title'];
            }
        }

        if (!empty($data['cid'])) {
            $query = $db->getQuery(true);
            $query->select('id as cid, category_name as title');
            $query->from('#__catalogue_category');
            $query->where('id IN (' . implode(',', $data['cid']) . ')');

            $db->setQuery($query);
            $categories = $db->loadAssocList('cid');

            foreach ($items as $item) {
                if (isset($item->cid))
                    $item->title = $categories[$item->cid]['title'];
            }
        }

        return $items;

    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'fp.*'
            )
        );

        $query->from($db->quoteName('#__catalogue_frontpage') . ' AS fp');


        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('sec.id = ' . (int)substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('(sec.section_name LIKE ' . $search . ' OR sec.alias LIKE ' . $search . ')');
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
        $id .= ':' . $this->getState('filter.access');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.section_id');
        $id .= ':' . $this->getState('filter.supersection_id');

        return parent::getStoreId($id);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication('administrator');

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
        $this->setState('filter.published', $published);

        $supersectionId = $this->getUserStateFromRequest($this->context . '.filter.supersection_id', 'filter_supersection_id', '');
        $this->setState('filter.supersection_id', $supersectionId);

        $id = $this->getUserStateFromRequest($this->context . '.section.id', 'id', 0, 'int');
        $this->setState('section.id', $id);

        $params = JComponentHelper::getParams('com_catalogue');
        $this->setState('params', $params);

        parent::populateState('fp.ordering', 'asc');
    }
}
