<?php


defined('_JEXEC') or die;

JLoader::register('CatalogueHelper', JPATH_COMPONENT.'/helpers/catalogue.php');


class CatalogueViewSupersections extends JViewLegacy
{
	protected $supersections;

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

		CatalogueHelper::addSubmenu('supersections');

		$this->addToolbar();
		

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		
		$canDo = CatalogueHelper::getActions($this->state->get('filter.cat_id'));

		$bar = JToolBar::getInstance('toolbar');
		
		JToolbarHelper::title(JText::_('COM_SUPERSECTION_MANAGER'), 'component.png');
		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('supersection.add');
		}

		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('supersection.edit');
		}
		
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'supersections.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('supersections.trash');
		}
		
		JHtmlSidebar::setAction('index.php?option=com_catalogue&view=categories');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);
	}
	
	protected function getSortFields()
	{
		return array(
			'ssec.supersection_name' => JText::_('COM_CATALOGUE_HEADING_NAME'),
			'ssec.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'ssec.published' => JText::_('JSTATUS'),
			'ssec.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}