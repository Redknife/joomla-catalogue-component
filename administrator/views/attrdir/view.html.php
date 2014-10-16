<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('CatalogueHelper', JPATH_COMPONENT . '/helpers/catalogue.php');

/**
 * View to edit a banner.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 * @since       1.5
 */
class CatalogueViewAttrDir extends JViewLegacy
{
    protected $form;

    protected $item;

    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {

        // Initialiase variables.
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since    1.6
     */
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user = JFactory::getUser();
        $userId = $user->get('id');
        $isNew = ($this->item->id == 0);
        // Since we don't track these assets at the item level, use the manufacturer id.
        $canDo = CatalogueHelper::getActions($this->item->id, 0);

        JToolbarHelper::title($isNew ? JText::_('COM_CATALOGUE_MANAGER_ATTRDIR_NEW') : JText::_('COM_CATALOGUE_MANAGER_ATTRDIR_EDIT'));

        // If not checked out, can save the item.

        JToolbarHelper::apply('attrdir.apply');
        JToolbarHelper::save('attrdir.save');

        JToolbarHelper::save2new('attrdir.save2new');

        // If an existing item, can save to a copy.
        if (!$isNew) {
            JToolbarHelper::save2copy('attrdir.save2copy');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('attrdir.cancel');
        } else {
            JToolbarHelper::cancel('attrdir.cancel', 'JTOOLBAR_CLOSE');
        }

        JToolbarHelper::divider();
    }
}
