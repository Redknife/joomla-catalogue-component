<?php


defined('_JEXEC') or die;

JLoader::register('CatalogueHelper', JPATH_COMPONENT . '/helpers/catalogue.php');


class CatalogueViewAttrs extends JViewLegacy
{

    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        CatalogueHelper::addSubmenu('attrs');

        $this->addToolbar();


        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        require_once JPATH_COMPONENT . '/helpers/catalogue.php';

        $canDo = CatalogueHelper::getActions($this->state->get('filter.attr_id'));

        $bar = JToolBar::getInstance('toolbar');

        JToolbarHelper::title(JText::_('COM_MANUFACTURER_MANAGER'), 'component.png');
        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('attr.add');
        }

        if (($canDo->get('core.edit'))) {
            JToolbarHelper::editList('attr.edit');
        }

        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            JToolbarHelper::deleteList('', 'attrs.delete', 'JTOOLBAR_EMPTY_TRASH');
        } elseif ($canDo->get('core.edit.state')) {
            JToolbarHelper::trash('attrs.trash');
        }

        JHtmlSidebar::setAction('index.php?option=com_catalogue&view=manfacturers');

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'filter_published',
            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
        );

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_ATTRDIR'),
            'filter_attrdir_id',
            JHtml::_('select.options', CatalogueHelper::getAttrDirsOptions(), 'value', 'text', $this->state->get('filter.attrdir_id'))
        );


    }

    protected function getSortFields()
    {
        return array(
            'a.attr_name' => JText::_('COM_CATALOGUE_ATTR_NAME'),
            'a.attr_type' => JText::_('COM_CATALOGUE_ATTR_TYPE'),
            'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'a.id' => JText::_('JGRID_HEADING_ID')
        );
    }
}
