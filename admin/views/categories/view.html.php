<?php


defined('_JEXEC') or die;

JLoader::register('CatalogueHelper', JPATH_COMPONENT.'/helpers/catalogue.php');


class CatalogueViewCategories extends JViewLegacy
{
	protected $categories;

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

		CatalogueHelper::addSubmenu('categories');

		$this->addToolbar();
		

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/catalogue.php';
		
		$canDo = CatalogueHelper::getActions($this->state->get('filter.category_id'));

		$bar = JToolBar::getInstance('toolbar');
		
		JToolbarHelper::title(JText::_('COM_CATEGORY_MANAGER'), 'component.png');
		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('category.add');
		}

		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('category.edit');
		}
		
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'categories.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('categories.trash');
		}
		
		JHtmlSidebar::setAction('index.php?option=com_catalogue&view=categories');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_SECTION'),
			'filter_section_id',
			JHtml::_('select.options', CatalogueHelper::getSectionsOptions(), 'value', 'text', $this->state->get('filter.section_id'))
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_SUPERSECTION'),
			'filter_supersection_id',
			JHtml::_('select.options', CatalogueHelper::getSupersectionsOptions(), 'value', 'text', $this->state->get('filter.supersection_id'))
		);
	}

	protected function getSortFields()
	{
		return array(
			'cat.category_name' => JText::_('COM_CATALOGUE_HEADING_NAME'),
			'sec.section_name' => JText::_('COM_CATALOGUE_HEADING_SECTION'),
			'ss.supersection_name' => JText::_('COM_CATALOGUE_HEADING_SUPERSECTION'),
			'cat.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'cat.published' => JText::_('JSTATUS'),
			'cat.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
/// tafuck??
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