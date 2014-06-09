<?php

defined('_JEXEC') or die;

class catalogueViewCategory extends JViewLegacy
{
	protected $items;
	protected $state;
	protected $pagination;

	public function display($tpl = null)
	{
		
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');
	
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		$registry = new JRegistry($this->state->get('category.params'));
		$layout = str_replace('_:', '', $registry->get('category_layout', 'default'));
		$this->setLayout($layout);
		
		$this->_prepareDocument();
		
		parent::display($tpl);
	}
	
	private function _prepareDocument()
	{

		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title 		= null;
		$metadata 	= new JRegistry($this->state->get('category.metadata'));
		
		// Ссылка на активный пункт меню
		$menu = $menus->getActive();
		
		// Проверка привязана ли категория к меню
		$cid = (int) @$menu->query['cid'];
		
		if ($menu && $cid == $this->state->get('category.id'))
		{
			//если привязана к меню то берем TITLE из меню
			$title = $menu->params->get('page_title');
		}
		else
		{
			//если нет то берем TITLE из настрое категории (по умолчанию название категории)
			$title = $metadata->get('metatitle', $this->state->get('category.name'));
		}
		
		// Установка <TITLE>
		
		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		
		$this->document->setTitle($title);
		
		// Устновка метаданных 
		
		
		if ($metadesc = $metadata->get('metadesc', ''))
		{
			$this->document->setDescription($metadesc);
		}
		elseif (!$metadesc && $menu->params->get('menu-meta_description'))
		{
			$this->document->setDescription($menu->params->get('menu-meta_description'));
		}

		if ($metakey = $metadata->get('metakey', ''))
		{
			$this->document->setMetadata('keywords', $metakey);
		}
		elseif (!$metakey && $menu->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $menu->params->get('menu-meta_keywords'));
		}

		if ($robots = $metadata->get('robots', ''))
		{
			$this->document->setMetadata('robots', $robots);
		}
	}
}