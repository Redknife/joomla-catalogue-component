<?php

defined('_JEXEC') or die;

class CatalogueModelAttrDir extends JModelAdmin
{

    public function getTable($type = 'AttrDir', $prefix = 'CatalogueTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_catalogue.attrdir', 'attrdir', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function save($data)
    {
        return parent::save($data);
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_catalogue.edit.attrdir.data', array());

        if (empty($data)) {
            $data = $this->getItem();


        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        if ($item) {
            $query = $this->_db->getQuery(true);
            $query->select('ac.category_id as value');
            $query->from('#__catalogue_attrdir_category AS ac');
            $query->where('ac.attrdir_id = ' . (int)$item->id);
            $this->_db->setQuery($query);

            $item->category_id = $this->_db->loadObjectList();
        }
        return $item;
    }

}
