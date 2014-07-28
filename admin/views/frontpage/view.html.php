<?php


defined('_JEXEC') or die;

JLoader::register('CatalogueHelper', JPATH_COMPONENT.'/helpers/catalogue.php');


class CatalogueViewFrontpage extends JViewLegacy
{
	protected $sections;

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

		CatalogueHelper::addSubmenu('frontpage');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		
		$canDo = CatalogueHelper::getActions($this->state->get('filter.cat_id'));

		$bar = JToolBar::getInstance('toolbar');
		
		JToolbarHelper::title(JText::_('COM_SECTION_MANAGER'), 'component.png');

		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('section.edit');
		}
		
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'sections.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('sections.trash');
		}
		
		JHtmlSidebar::setAction('index.php?option=com_catalogue&view=frontpage');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
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
			'sec.section_name' => JText::_('COM_CATALOGUE_HEADING_NAME'),
			'ss.supersection_name' => JText::_('COM_CATALOGUE_HEADING_SUPERSECTION'),
			'sec.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'sec.published' => JText::_('JSTATUS'),
			'sec.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}