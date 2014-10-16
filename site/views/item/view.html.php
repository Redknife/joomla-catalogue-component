<?php

defined('_JEXEC') or die;

class catalogueViewItem extends JViewLegacy
{
    protected $item;
    protected $state;
    protected $watchlist;


    public function display($tpl = null)
    {

        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        $this->watchlist = CatalogueHelper::getWatchListItems();

        $this->_prepareDocument();
        parent::display($tpl);
    }

    protected function _prepareDocument()
    {

        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $pathway = $app->getPathway();
        $title = null;
        $metadata = new JRegistry($this->state->get('item.metadata'));


        // Ссылка на активный пункт меню
        $menu = $menus->getActive();

        // Проверка привязана ли категория к меню
        $cid = (int)@$menu->query['cid'];

        $this->category = JCategories::getInstance('Catalogue')->get($cid);


        if ($menu && ($menu->query['option'] != 'com_catalogue' || $menu->query['view'] == 'category' || $cid != $this->category->id)) {
            // $path = array(array('title' => $this->item->item_name, 'link' => ''));
            // $category = $this->category->getParent();

            // while (($menu->query['option'] != 'com_catalogue' || $menu->query['view'] == 'category' || $cid != $category->id) && $category->id > 1)
            // {
            // 	$path[] = array('title' => $category->title, 'link' => CatalogueHelperRoute::getCategoryRoute($category->id));
            // 	$category = $category->getParent();
            // }

            // $path = array_reverse($path);

            // foreach ($path as $item)
            // {
            // 	$pathway->addItem($item['title'], $item['link']);
            // }
            $pathway->addItem($this->item->item_name, '');
        }
        // var_dump($metadata);

        if ($menu && $cid == $this->state->get('item.id')) {
            //если привязана к меню то берем TITLE из меню
            $title = $menu->params->get('page_title');
        } else {
            //если нет то берем TITLE из настрое категории (по умолчанию название категории)
            $title = $metadata->get('metatitle', $this->state->get('item.name'));
        }

        // Установка <TITLE>

        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }

        $this->document->setTitle($title);

        // Устновка метаданных


        if ($metadesc = $metadata->get('metadesc', '')) {
            $this->document->setDescription($metadesc);
        } elseif (!$metadesc && $menu && $menu->params->get('menu-meta_description')) {
            $this->document->setDescription($menu->params->get('menu-meta_description'));
        }

        if ($metakey = $metadata->get('metakey', '')) {
            $this->document->setMetadata('keywords', $metakey);
        } elseif (!$metakey && $menu && $menu->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $menu->params->get('menu-meta_keywords'));
        }

        if ($robots = $metadata->get('robots', '')) {
            $this->document->setMetadata('robots', $robots);
        }
    }
}