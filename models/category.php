<?php


defined('_JEXEC') or die;


class CatalogueModelCategory extends JModelList
{

    public $_context = 'com_catalogue.category';

    protected $_extension = 'com_catalogue';

    private $_parent = null;

    private $_items = null;


    public function getListQuery()
    {
        // $this->setState('list.limit', 9);
        $catid = $this->getState('category.id', 0);

        $db		= JFactory::getDbo();
        $query	= $db->getQuery(true);
        $query->select('itm.*, cat.title as category_name');
        $query->from('#__catalogue_item AS itm');
        $query->join('LEFT', '#__categories AS cat ON cat.id = itm.category_id');
        $query->where('itm.state = 1  && itm.published = 1 && itm.category_id = '.$catid);
        $query->order('itm.ordering');

        $db->setQuery($query);

        return $query;
    }


    protected function populateState($ordering = NULL, $direction = NULL)
    {
        $app = JFactory::getApplication('site');
        $pk  = $app->input->getInt('сid');

        $this->setState('category.id', $pk);

        $id = $app->input->getUInt('id');
        $this->setState('item.id', $id);

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

    }
}