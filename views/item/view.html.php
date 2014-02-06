<?php

defined('_JEXEC') or die;

class catalogueViewItem extends JViewLegacy
{
	protected $item;
	protected $state;


	public function display($tpl = null)
	{
		
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		$this->more = $this->get('More');
		$watchlist = CatalogueHelper::getWatchListItems();
		
		$this->assignRef('item', $this->item);
		$this->assignRef('state', $this->state);
		$this->assignRef('more', $this->more);
		//$this->pageclass_sfx = htmlspecialchars($this->item->params->get('pageclass_sfx'));

		//$this->_prepareDocument();
		parent::display($tpl);
	}
}