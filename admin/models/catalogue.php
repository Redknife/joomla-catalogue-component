<?php

defined('_JEXEC') or die;

class CatalogueModelCatalogue extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'c.id',
                'category_id', 'c.category_id',
                'item_name', 'c.item_name',
                'section_name', 's.section_name',
                'manufacturer_name','mf.manufacturer_name',
                'country_name', 'ct.country_name',
                'section_id', 'c.section_id',
                'manufacturer_id','c.manufacturer_id',
                'country_id', 'mf.country_id',
                'price', 'c.price',
                'published', 'c.published',
                'ordering', 'c.ordering',
                'created_by', 'c.created_by'
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db		= $this->getDbo();
        $query	= $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'c.*'
            )
        );
        $query->from($db->quoteName('#__catalogue_item').' AS c');

        // Join over the categories.
        $query->select('cat.title AS category_name')
            ->join('LEFT', '#__categories AS cat ON cat.id = c.category_id');

        $query->select('mf.manufacturer_name')
            ->join('LEFT', '#__catalogue_manufacturer AS mf ON mf.id = c.manufacturer_id');

        $query->select('ct.country_name')
            ->join('LEFT', '#__catalogue_country AS ct ON ct.id = mf.country_id');

        $query->select('u.name AS username')
            ->join('LEFT', '#__users AS u ON u.id = c.created_by');

        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('c.state = '.(int) $published);
        } elseif ($published === '') {
            $query->where('(c.state IN (0, 1))');
        }

        // Filter by category.
        $categoryId = $this->getState('filter.cat_id');
        if (is_numeric($categoryId)) {
            $query->where('c.category_id = '.(int) $categoryId);
        }

        // Filter by manufacturer.
        $manufacturerId = $this->getState('filter.manufacturer_id');
        if (is_numeric($manufacturerId)) {
            $query->where('c.manufacturer_id = '.(int) $manufacturerId);
        }

        // Filter by country.
        $countryId = $this->getState('filter.country_id');
        if (is_numeric($countryId)) {
            $query->where('mf.country_id = '.(int) $countryId);
        }

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('c.published = ' . (int) $published);
        }
        elseif ($published === '') {
            $query->where('(c.published = 0 OR c.published = 1)');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('c.id = '.(int) substr($search, 3));
            } else {
                $search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('(c.name LIKE '.$search.' OR c.intro LIKE '.$search.')');
            }
        }

        // Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering', 'ordering');
        $orderDirn	= $this->state->get('list.direction', 'ASC');
        if ($orderCol == 'ordering' || $orderCol == 'category_name') {
            $orderCol = 'c.item_name '.$orderDirn.', c.ordering';
        }

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
        $id	.= ':'.$this->getState('filter.published');
        $id	.= ':'.$this->getState('filter.category_id');
        $id	.= ':'.$this->getState('filter.section_id');
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

        $access = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
        $this->setState('filter.access', $access);

        $published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', '');
        $this->setState('filter.category_id', $categoryId);

        $sectionId = $this->getUserStateFromRequest($this->context.'.filter.section_id', 'filter_section_id', '');
        $this->setState('filter.section_id', $sectionId);

        $manufacturerId = $this->getUserStateFromRequest($this->context.'.filter.manufacturer_id', 'filter_manufacturer_id', '');
        $this->setState('filter.manufacturer_id', $manufacturerId);

        $countryId = $this->getUserStateFromRequest($this->context.'.filter.country_id', 'filter_country_id', '');
        $this->setState('filter.country_id', $countryId);

        $id = $this->getUserStateFromRequest($this->context.'.item.id', 'id', 0, 'int');
        $this->setState('item.id', $id);

        $params = JComponentHelper::getParams('com_catalogue');
        $this->setState('params', $params);

        parent::populateState('c.item_name', 'asc');
    }
}
