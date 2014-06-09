<?php


defined('_JEXEC') or die;


class CatalogueController extends JControllerLegacy
{
	
	protected $default_view = 'catalogue';
	
	public function display($cachable = false, $urlparams = false)
	{
		
		require_once JPATH_COMPONENT.'/helpers/catalogue.php';


		$view   = $this->input->get('view', 'catalogue');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
		
		// Check for edit form.
		if ($view == 'item' && $layout == 'edit' && !$this->checkEditId('com_catalogue.edit.item', $id))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_catalogue&view=catalogue', false));

			return false;
		}
		
		if ($view == 'category' && $layout == 'edit' && !$this->checkEditId('com_catalogue.edit.category', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_catalogue&view=categories', false));

			return false;
		}
		
		if ($view == 'country' && $layout == 'edit' && !$this->checkEditId('com_catalogue.edit.country', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_catalogue&view=countries', false));

			return false;
		}		
		
		parent::display();

		return $this;
	}
}
