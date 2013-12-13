<?php

defined('_JEXEC') or die;

class CatalogueViewSection extends JViewLegacy
{
	protected $items;

	protected $state;

	public function display($tpl = null)
	{
		$model = $this->getModel();
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		$this->assignRef('items', $this->items);
		$this->assignRef('state', $this->state);
		//$this->pageclass_sfx = htmlspecialchars($this->item->params->get('pageclass_sfx'));

		//$this->_prepareDocument();

		parent::display($tpl);
	}
}