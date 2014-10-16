<?php

defined('_JEXEC') or die;

class CatalogueModelManufacturer extends JModelAdmin
{

    public function getTable($type = 'Manufacturers', $prefix = 'CatalogueTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_catalogue.manufacturer', 'manufacturer', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function save($data)
    {
        if (isset($data['alias']) && empty($data['alias'])) {
            $data['alias'] = ru_RULocalise::transliterate($data['manufacturer_name']);
            $data['alias'] = preg_replace('#\W#', '-', $data['alias']);
            $data['alias'] = preg_replace('#[-]+#', '-', $data['alias']);
        }

        return parent::save($data);
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_catalogue.edit.manufacturer.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            // Prime some default values.
            if ($this->getState('catalogue.id') == 0) {
                $data->set('country_id', $app->input->getInt('catid', $app->getUserState('com_catalogue.manufacturer.filter.country_id')));
            }
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        return $item;
    }

}
