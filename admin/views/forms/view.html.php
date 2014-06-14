<?php


defined('_JEXEC') or die;

JLoader::register('CatalogueHelper', JPATH_COMPONENT.'/helpers/catalogue.php');

class CatalogueViewForms extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		CatalogueHelper::addSubmenu('forms');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/catalogue.php';
		
		$canDo = CatalogueHelper::getActions($this->state->get('filter.form_id'));

		$bar = JToolBar::getInstance('toolbar');
		
		JToolbarHelper::title(JText::_('COM_FORM_MANAGER'), 'component.png');
		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('form.add');
		}

		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('form.edit');
		}
		
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'forms.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('forms.trash');
		}
		
		JHtmlSidebar::setAction('index.php?option=com_catalogue&view=forms');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);
	}
	
	protected function getSortFields()
	{
		return array(
			'f.form_name' => JText::_('COM_CATALOGUE_HEADING_NAME'),
			'f.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'f.published' => JText::_('JSTATUS'),
			'f.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
/*
protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/catalogue.php';
		
		$user = JFactory::getUser();
		
		$canDo = CatalogueHelper::getActions($this->state->get('filter.cat_id'));

				
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_CATALOGUE_MANAGER'), 'component.png');
		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('item.add');
		}

		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('item.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			if ($this->state->get('filter.state') != 2)
			{
				JToolbarHelper::publish('item.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('item.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}

			if ($this->state->get('filter.state') != -1)
			{
				if ($this->state->get('filter.state') != 2)
				{
					JToolbarHelper::archiveList('item.archive');
				}
				elseif ($this->state->get('filter.state') == 2)
				{
					JToolbarHelper::unarchiveList('item.publish');
				}
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::checkin('item.checkin');
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'item.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('item.trash');
		}


		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_catalogue');
		}
		

		JHtmlSidebar::setAction('index.php?option=com_catalogue&view=items');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_CATEGORY'),
			'filter_cat_id',
			JHtml::_('select.options', CatalogueHelper::getCategoriesOptions(), 'value', 'text', $this->state->get('filter.cat_id'))
		);

	}
*/