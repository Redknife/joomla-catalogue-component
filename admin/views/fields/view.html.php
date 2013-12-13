<?php


defined('_JEXEC') or die;

JLoader::register('CatalogueHelper', JPATH_COMPONENT.'/helpers/catalogue.php');


class CatalogueViewFields extends JViewLegacy
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

		CatalogueHelper::addSubmenu('fields');

		$this->addToolbar();
		

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/catalogue.php';
		
		$canDo = CatalogueHelper::getActions($this->state->get('filter.field_id'));

		$bar = JToolBar::getInstance('toolbar');
		
		JToolbarHelper::title(JText::_('COM_FIELD_MANAGER'), 'component.png');
		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('field.add');
		}

		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('field.edit');
		}
		
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'fields.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('fields.trash');
		}
		
		JHtmlSidebar::setAction('index.php?option=com_catalogue&view=fields');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_FORM'),
			'filter_form_id',
			JHtml::_('select.options', CatalogueHelper::getFormsOptions(), 'value', 'text', $this->state->get('filter.form_id'))
		);
	}
	
	protected function getSortFields()
	{
		return array(
			'fl.field_name' => JText::_('COM_CATALOGUE_HEADING_NAME'),
			'fm.form_name' => JText::_('COM_CATALOGUE_HEADING_FORM'),
			'fl.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'fl.published' => JText::_('JSTATUS'),
			'fl.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
