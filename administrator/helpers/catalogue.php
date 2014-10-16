<?php
defined('_JEXEC') or die;


class CatalogueHelper
{

    public static $extension = 'com_catalogue';

    public static function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_CATALOGUE_SUBMENU_CATALOGUE'),
            'index.php?option=com_catalogue&view=catalogue',
            $vName == 'catalogue'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_CATALOGUE_SUBMENU_CATEGORIES'),
            'index.php?option=com_categories&extension=com_catalogue',
            $vName == 'categories'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_CATALOGUE_SUBMENU_MANUFACTURERS'),
            'index.php?option=com_catalogue&view=manufacturers',
            $vName == 'manufacturers'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_CATALOGUE_SUBMENU_COUNTRIES'),
            'index.php?option=com_catalogue&view=countries',
            $vName == 'countries'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_CATALOGUE_SUBMENU_ATTRDIRS'),
            'index.php?option=com_catalogue&view=attrdirs',
            $vName == 'attrdirs'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_CATALOGUE_SUBMENU_ATTRS'),
            'index.php?option=com_catalogue&view=attrs',
            $vName == 'attrs'
        );


    }


    public static function getActions()
    {
        $user = JFactory::getUser();
        $result = new JObject;

        $actions = JAccess::getActions('com_catalogue', 'component');

        foreach ($actions as $action) {
            $result->set($action->name, $user->authorise($action->name, 'com_catalogue'));
        }

        return $result;
    }


    public static function getItemsOptions($exclude = false)
    {
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, item_name As text');
        $query->from('#__catalogue_item AS i');
        $query->order('i.item_name');

        // Get the options.
        $db->setQuery($query);

        try {
            $options = $db->loadObjectList();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage());
        }

        return $options;
    }

    public static function getCountriesOptions()
    {
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, country_name As text');
        $query->from('#__catalogue_country AS ctr');
        $query->order('ctr.country_name');

        // Get the options.
        $db->setQuery($query);

        try {
            $options = $db->loadObjectList();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage());
        }

        return $options;
    }

    public static function getManufacturersOptions()
    {
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, manufacturer_name As text');
        $query->from('#__catalogue_manufacturer AS ctr');
        $query->order('ctr.manufacturer_name');

        // Get the options.
        $db->setQuery($query);

        try {
            $options = $db->loadObjectList();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage());
        }

        return $options;
    }

    public static function getAttrDirsOptions()
    {
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, dir_name As text');
        $query->from('#__catalogue_attrdir AS d');
        $query->order('d.dir_name');

        // Get the options.
        $db->setQuery($query);

        try {
            $options = $db->loadObjectList();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage());
        }

        return $options;
    }

}
