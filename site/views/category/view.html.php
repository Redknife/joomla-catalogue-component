<?php

defined('_JEXEC') or die;

class catalogueViewCategory extends JViewCategory
{
    protected $items;
    protected $state;
    protected $category;
    protected $pagination;

    public function display($tpl = null)
    {

        parent::commonCategoryDisplay();

        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');

        $this->filters = $this->get('Filters');

        $this->params = $this->state->get('params');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }


        parent::display($tpl);
    }

    protected function prepareDocument()
    {

        parent::prepareDocument();

        $title = null;
        $metadata = new JRegistry($this->state->get('category.metadata'));

        //$this->category = JCategories::getInstance('Catalogue')->get($this->state->get('category.id'));

        $menu = $this->menu;
        $cid = (int)@$menu->query['cid'];

        // if ($menu && ($menu->query['option'] != 'com_catalogue' || $menu->query['view'] == 'category' || $cid != $this->category->id))
        // {
        // $path = array(array('title' => $this->category->title, 'link' => ''));
        // $category = $this->category->getParent();

        // while (($menu->query['option'] != 'com_catalogue' || $menu->query['view'] == 'category' || $cid != $category->id) && $category->id > 1)
        // {
        // 	$path[] = array('title' => $category->title, 'link' => CatalogueHelperRoute::getCategoryRoute($category->id));
        // 	$category = $category->getParent();
        // }

        // $path = array_reverse($path);

        // foreach ($path as $item)
        // {
        // 	$this->pathway->addItem($item['title'], $item['link']);
        // }
        // }

        if ($menu && $cid == $this->category->id) {
            //если привязана к меню то берем TITLE из меню
            $title = $menu->params->get('page_title');
        } else {
            //если нет то берем TITLE из настрое категории (по умолчанию название категории)
            $title = $metadata->get('metatitle', $this->category->title);
        }

        $app = JFactory::getApplication();
        // Установка <TITLE>

        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }

        $this->document->setTitle($title);

        // Устновка метаданных


        if ($metadesc = $metadata->get('metadesc', '')) {
            $this->document->setDescription($metadesc);
        } elseif (!$metadesc && $menu->params->get('menu-meta_description')) {
            $this->document->setDescription($menu->params->get('menu-meta_description'));
        }

        if ($metakey = $metadata->get('metakey', '')) {
            $this->document->setMetadata('keywords', $metakey);
        } elseif (!$metakey && $menu->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $menu->params->get('menu-meta_keywords'));
        }

        if ($robots = $metadata->get('robots', '')) {
            $this->document->setMetadata('robots', $robots);
        }

        //parent::addFeed();
    }
}