<?php


defined('_JEXEC') or die;

JLoader::register('CatalogueHelper', JPATH_COMPONENT . '/helpers/catalogue.php');


class CatalogueViewAttrDirs extends JViewLegacy
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

        CatalogueHelper::addSubmenu('attrdirs');

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
            JToolbarHelper::addNew('attrdir.add');
        }

        if (($canDo->get('core.edit'))) {
            JToolbarHelper::editList('attrdir.edit');
        }

        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            JToolbarHelper::deleteList('', 'attrdirs.delete', 'JTOOLBAR_EMPTY_TRASH');
        } elseif ($canDo->get('core.edit.state')) {
            JToolbarHelper::trash('attrdirs.trash');
        }

        JHtmlSidebar::setAction('index.php?option=com_catalogue&view=manfacturers');

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'filter_published',
            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
        );


    }

    protected function getSortFields()
    {
        return array(
            'd.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'd.dir_name' => JText::_('COM_CATALOGUE_ATTR_NAME'),
            'd.id' => JText::_('JGRID_HEADING_ID')
        );
    }
}
