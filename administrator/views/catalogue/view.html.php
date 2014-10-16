<?php defined('_JEXEC') or die;

JLoader::register('CatalogueHelper', JPATH_COMPONENT . '/helpers/catalogue.php');

class CatalogueViewCatalogue extends JViewLegacy
{
    protected $items;

    protected $pagination;

    protected $state;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->categories = $this->get('Categories');

        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        CatalogueHelper::addSubmenu('catalogue');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        require_once JPATH_COMPONENT . '/helpers/catalogue.php';

        $user = JFactory::getUser();

        $canDo = CatalogueHelper::getActions();

        $bar = JToolBar::getInstance('toolbar');

        JToolbarHelper::title(JText::_('COM_CATALOGUE_MANAGER'), 'component.png');
        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('item.add');
        }

        if (($canDo->get('core.edit'))) {
            JToolbarHelper::editList('item.edit');
        }

        if ($canDo->get('core.edit.state')) {
            if ($this->state->get('filter.state') != 2) {
                JToolbarHelper::publish('item.publish', 'JTOOLBAR_PUBLISH', true);
                JToolbarHelper::unpublish('item.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            }

            if ($this->state->get('filter.state') != -1) {
                if ($this->state->get('filter.published') != 2) {
                    JToolbarHelper::archiveList('item.archive');
                } elseif ($this->state->get('filter.state') == 2) {
                    JToolbarHelper::unarchiveList('item.publish');
                }
            }
        }

        if ($canDo->get('core.edit.state')) {
            JToolbarHelper::checkin('item.checkin');
        }

        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            JToolbarHelper::deleteList('', 'catalogue.delete', 'JTOOLBAR_EMPTY_TRASH');
        } elseif ($canDo->get('core.edit.state')) {
            JToolbarHelper::trash('catalogue.trash');
        }


        if ($canDo->get('core.admin')) {
            JToolbarHelper::preferences('com_catalogue');
        }

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_CATEGORY'),
            'filter_category_id',
            JHtml::_('select.options', JHtml::_('category.options', 'com_catalogue'), 'value', 'text', $this->state->get('filter.category_id'))
        );

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_MANUFACTURER'),
            'filter_manufacturer_id',
            JHtml::_('select.options', CatalogueHelper::getManufacturersOptions(), 'value', 'text', $this->state->get('filter.manufacturer_id'))
        );

    }

    protected function getSortFields()
    {
        return array(
            'c.item_name' => JText::_('COM_CATALOGUE_HEADING_NAME'),
            'mf.manufacturer_name' => JText::_('COM_CATALOGUE_HEADING_MANUFACTURER'),
            'category_title' => JText::_('JCATEGORY'),
            'c.price' => JText::_('COM_CATALOGUE_HEADING_PRICE'),
            'c.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'c.published' => JText::_('JSTATUS'),
            'c.id' => JText::_('JGRID_HEADING_ID')
        );
    }

}
