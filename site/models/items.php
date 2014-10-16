<?php
defined('_JEXEC') or die;

class CatalogueModelItems extends JModelList
{

    public $_context = 'com_catalogue.items';

    protected $_extension = 'com_catalogue';

    private $_parent = null;

    private $_items = null;

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'itm.id',
                'item_name', 'itm.item_name',
                'price', 'itm.price',
                'alias', 'itm.alias',
                'state', 'itm.state',
                'ordering', 'itm.ordering',
                'published', 'itm.published'
            );
        }
        parent::__construct($config);
    }

    public function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $categoryId = $this->getState('filter.category_id');
        // $price_cat = $this->getState('filters.price_cat', 0);

        if (is_numeric($categoryId)) {
            $type = $this->getState('filter.category_id.include', true) ? '= ' : '<> ';

            // Add subcategory check
            $includeSubcategories = $this->getState('filter.subcategories', false);
            $categoryEquals = 'itm.category_id ' . $type . (int)$categoryId;

            if ($includeSubcategories) {
                $levels = (int)$this->getState('filter.max_category_levels', '1');

                // Create a subquery for the subcategory list
                $subQuery = $db->getQuery(true)
                    ->select('sub.id')
                    ->from('#__categories as sub')
                    ->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt')
                    ->where('this.id = ' . (int)$categoryId);

                if ($levels >= 0) {
                    $subQuery->where('sub.level <= this.level + ' . $levels);
                }

                // Add the subquery to the main query
                $query->where('(' . $categoryEquals . ' OR itm.category_id IN (' . $subQuery->__toString() . '))');
            } else {
                $query->where($categoryEquals);
            }
        } elseif (is_array($categoryId) && (count($categoryId) > 0)) {
            JArrayHelper::toInteger($categoryId);
            $categoryId = implode(',', $categoryId);

            if (!empty($categoryId)) {
                $type = $this->getState('filter.category_id.include', true) ? 'IN' : 'NOT IN';
                $query->where('itm.category_id ' . $type . ' (' . $categoryId . ')');
            }
        }
        // $price_cat != 0 ? $price_category = ' AND itm.price_cat = '.$price_cat : $price_category = '';
        $params = $this->state->params;

        $params->get('catalogue_sort') == 1 ? $ordering = 'ordering' : $ordering = 'item_name';

        $query->select('itm.*, cat.title AS category_name, cat.description AS category_description')
            ->from('#__catalogue_item AS itm')
            ->join('LEFT', '#__categories as cat ON itm.category_id = cat.id')
            ->where('itm.state = 1 AND itm.published = 1');


        $sphinx_ids = $this->getState('filter.sphinx_ids', array());
        if (is_array($sphinx_ids) && !empty($sphinx_ids)) {
            $ids = implode(',', $sphinx_ids);
            $query->where('itm.id IN (' . $ids . ')');
        }


        $query->order($this->getState('list.ordering', 'itm.ordering') . ' ' . $this->getState('list.direction', 'ASC'));
        return $query;
    }

    public function getItem($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('itm.*');
        $query->from('#__catalogue_item AS itm');
        $query->where('itm.id = ' . $id);
        $db->setQuery($query);
        $this->_items = $db->loadObject();

        return $this->_items;
    }

    public function getHot()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('i.*');
        $query->from('#__catalogue_item AS i');
        $query->where('i.state = 1  AND i.published = 1');
        $query->where('i.sticker = 1');

        $query->order('i.ordering');

        $db->setQuery($query, 0, 4);

        $result = $db->loadObjectList();

        return $result;
    }

    public function getNew()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('i.*');
        $query->from('#__catalogue_item AS i');
        $query->where('i.state = 1  AND i.published = 1');
        $query->where('i.sticker = 2');

        $query->order('i.ordering');

        $db->setQuery($query, 0, 4);

        $result = $db->loadObjectList();

        return $result;
    }

    public function getSale()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('i.*');
        $query->from('#__catalogue_item AS i');
        $query->where('i.state = 1  AND i.published = 1');
        $query->where('i.sticker = 3');

        $query->order('i.ordering');

        $db->setQuery($query, 0, 4);

        $result = $db->loadObjectList();

        return $result;
    }

    protected function populateState($ordering = NULL, $direction = NULL)
    {

        $app = JFactory::getApplication('site');

        // List state information
        $value = $app->input->get('limit', $app->get('list_limit', 0), 'uint');
        $this->setState('list.limit', $value);

        $value = $app->input->get('limitstart', 0, 'uint');
        $this->setState('list.start', $value);

        $orderCol = $app->input->get('filter_order', 'itm.ordering');

        if (!in_array($orderCol, $this->filter_fields)) {
            $orderCol = 'itm.ordering';
        }

        $this->setState('list.ordering', $orderCol);

        $listOrder = $app->input->get('filter_order_Dir', 'ASC');

        if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
            $listOrder = 'ASC';
        }

        $this->setState('list.direction', $listOrder);


        $catid = $app->input->getUInt('cid');
        $this->setState('filter.category_id', $catid);

        $sphinx_ids = $app->input->getArray('sphinx_ids');
        $this->setState('filter.sphinx_ids', $sphinx_ids);

        $id = $app->input->getUInt('id');
        $this->setState('item.id', $id);

        $db = JFactory::getDbo();

        $db->setQuery(
            $db->getQuery(true)
                ->select('title AS category_name, category_description')
                ->from('#__categories')
                ->where('state = 1 AND published AND id = ' . $catid)
        );
        $category = $db->loadObject();

        $this->setState('category.name', $category->category_name);
        $this->setState('category.desc', $category->category_description);

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

    }

}