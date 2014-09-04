<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('CatalogueHelper', JPATH_COMPONENT.'/helpers/catalogue.php');

/**
 * View to edit a banner.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 * @since       1.5
 */
class CatalogueViewItem extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;
	protected $pagination;
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state		= $this->get('State');
		$this->pagination	= $this->get('Pagination');
		
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
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		// Since we don't track these assets at the item level, use the category id.
		$canDo		= CatalogueHelper::getActions($this->item->id, 0);

		JToolbarHelper::title($isNew ? JText::_('COM_CATALOGUE_MANAGER_ITEM_NEW') : JText::_('COM_CATALOGUE_MANAGER_ITEM_EDIT'), 'banners.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit'))){
			JToolbarHelper::apply('item.apply');
			JToolbarHelper::save('item.save');

			if ($canDo->get('core.create')) {
				JToolbarHelper::save2new('item.save2new');
			}
		}

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolbarHelper::save2copy('item.save2copy');
		}

		if (empty($this->item->id))  {
			JToolbarHelper::cancel('item.cancel');
		}
		else {
			JToolbarHelper::cancel('item.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
