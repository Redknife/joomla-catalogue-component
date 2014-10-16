<?php

defined('_JEXEC') or die;

class CatalogueModelItem extends JModelAdmin
{


    public function getTable($type = 'Catalogue', $prefix = 'CatalogueTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_catalogue.item', 'item', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function save($data)
    {
        /*
        $params = array_map(array($this, '_restructData'), $data['params']['name'], $data['params']['price'], $data['params']['count']);
        $registry = new JRegistry($params);
        $data['params'] = $registry->toString();
        */
        $techs = array_map(array($this, '_restructTechData'), $data['techs']['name'], $data['techs']['value'], $data['techs']['show_short']);

        $registry = new JRegistry($techs);
        $data['techs'] = $registry->toString();

        if (isset($data['alias']) && empty($data['alias'])) {
            $data['alias'] = ru_RULocalise::transliterate($data['item_name']);
            $data['alias'] = preg_replace('#\W#', '-', $data['alias']);
            $data['alias'] = preg_replace('#[-]+#', '-', $data['alias']);
        }

        return parent::save($data);
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_catalogue.edit.item.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            // Prime some default values.
            if ($this->getState('item.id') == 0) {
                $data->set('cat_id', $app->input->getInt('catid', $app->getUserState('com_catalogue.catalogue.filter.cat_id')));
            }
        }
        return $data;
    }

    public function getItem($pk = null)
    {

        if ($result = parent::getItem($pk)) {
            // Convert the metadata field to an array.
            $metadata = new JRegistry;
            $metadata->loadString($result->metadata);
            $result->metadata = $metadata->toArray();

            $techs = new JRegistry;
            $techs->loadString($result->techs);
            $result->techs = $techs->toArray();

            $query = $this->_db->getQuery(true);
            $query->select('a.*, i.item_name as assoc_name')
                ->from('#__catalogue_assoc as a')
                ->join('LEFT', '#__catalogue_item as i ON i.id = a.assoc_id')
                ->where('a.item_id = ' . (int)$result->id)
                ->order('a.ordering ASC');
            $this->_db->setQuery($query);

            $result->assoc = $this->_db->loadObjectList();

            // load reviews..
            $query = $this->_db->getQuery(true);
            $query->select('rv.*')
                ->from('#__catalogue_item_review as rv')
                ->where('rv.item_id = ' . (int)$result->id)
                ->order('rv.ordering ASC');
            $this->_db->setQuery($query);

            $result->reviews = $this->_db->loadObjectList();
            // ..load reviews

            $query = $this->_db->getQuery(true);

            $query->select('a.id as attr_id, a.attr_name')
                ->from('#__catalogue_attr as a')
                ->where('a.published = 1');

            $this->_db->setQuery($query);

            $result->attrs = $this->_db->loadAssocList('attr_id');

            $query = $this->_db->getQuery(true);

            $query->select('a.id as attr_id, a.attrdir_id, d.dir_name, a.attr_name, a.attr_type, a.attr_default, 0 as attr_value, 0 as attr_price, \'\' as attr_image')
                ->from('#__catalogue_attr as a')
                ->join('LEFT', '#__catalogue_attrdir as d ON d.id = a.attrdir_id')
                ->where('a.published = 1 AND d.published = 1')
                // ->order('a.attrdir_id ASC')
                ->order('a.attrdir_id ASC, a.attr_name ASC')
                ->group('a.id');
            // echo $query;
            $this->_db->setQuery($query);

            $result->attrdirs = $this->_db->loadObjectList();

            $query = $this->_db->getQuery(true);

            $query->select('p.attr_id, p.attr_price')
                ->from('#__catalogue_attr_price as p')
                ->where('p.item_id = ' . (int)$result->id);

            $this->_db->setQuery($query);

            $attr_prices = $this->_db->loadAssocList('attr_id');

            $query = $this->_db->getQuery(true);

            $query->select('i.attr_id, i.attr_image')
                ->from('#__catalogue_attr_image as i')
                ->where('i.item_id = ' . (int)$result->id);

            $this->_db->setQuery($query);

            $attr_images = $this->_db->loadAssocList('attr_id');

            foreach ($result->attrdirs as $attr_dir) {

                if (isset($result->params['attr_' . $attr_dir->attr_id]))
                    $attr_dir->attr_value = $result->params['attr_' . $attr_dir->attr_id];

                if (isset($attr_prices[$attr_dir->attr_id]))
                    $attr_dir->attr_price = $attr_prices[$attr_dir->attr_id]['attr_price'];

                if (isset($attr_images[$attr_dir->attr_id]))
                    $attr_dir->attr_image = $attr_images[$attr_dir->attr_id]['attr_image'];

            }
            $result->attrs = JArrayHelper::toObject($result->attrs);
        }
        return $result;
    }

    protected function _restructData($name, $price, $count)
    {
        if ($name && $price && $count)
            return array('name' => $name, 'price' => $price, 'count' => $count);

    }

    protected function _restructTechData($name, $value, $show_short)
    {
        if ($name && $value)
            return array('name' => $name, 'value' => $value, 'show_short' => $show_short);

    }


}