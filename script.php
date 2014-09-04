<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of HelloWorld component
 */
class com_catalogueInstallerScript
{
    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent)
    {
        // Create categories for our component
        $basePath = JPATH_ADMINISTRATOR . '/components/com_categories';
        require_once $basePath . '/models/category.php';
        $config = array( 'table_path' => $basePath . '/tables');
        $model = new CategoriesModelCategory($config);
        $data = array( 'id' => 0, 'parent_id' => 1, 'level' => 1, 'path' => 'uncategorised', 'extension' => 'com_catalogue' , 'title' => 'Uncategorised', 'alias' => 'uncategorised', 'published' => 1, 'language' => '*');
        $status = $model->save($data);

        if(!$status)
        {
            JError::raiseWarning(500, JText::_('Unable to create default content category!'));
        }
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent)
    {
        // $parent is the class calling this method
        echo '<p>' . JText::_('COM_CATALOGUE_UNINSTALL_TEXT') . '</p>';
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent)
    {
        // $parent is the class calling this method
        echo '<p>' . JText::sprintf('COM_CATALOGUE_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent)
    {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        // echo '<p>' . JText::_('COM_CATALOGUE_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent)
    {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        // echo '<p>' . JText::_('COM_CATALOGUE_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
    }
}