<?php
defined('_JEXEC') or die;

class CatalogueModelHotitems extends JModelList
{

    public $_context = 'com_catalogue.hotitems';

    protected $_extension = 'com_catalogue';

    private $_parent = null;

    private $_items = null;


    public function getListQuery()
    {
        $this->setState('list.limit', 9);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('i.*, sec.id as sectionid, cat.id as categoryid');
        $query->from('#__catalogue_item AS i');
        $query->join('LEFT', '#__catalogue_category as cat ON cat.id = i.category_id');
        $query->join('LEFT', '#__catalogue_section as sec ON sec.id = cat.section_id');
        $query->where('i.state = 1  AND i.published = 1');
        $query->where('i.sticker = 2');
        $query->order('i.ordering');
        $db->setQuery($query);
        // $this->_items = $db->loadObjectList();

        // return $this->_items;
        return $query;
    }

}