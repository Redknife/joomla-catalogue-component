<?php


defined('_JEXEC') or die;

require_once(dirname(__FILE__).DS.'helper.php');

class CatalogueController extends JControllerLegacy
{
	public function search(){
 		$search = $this->input->get('search', '', 'string');

		parent::display();
    }
 
	public function addToFavorite()
	{
		$app = JFactory::getApplication();

		$favorite = $app->getUserState('com_catalogue.favorite');
		
		$order = JRequest::getVar('id', 0, 'get', 'int');
		
		if (!CatalogueHelper::isFavorite($order))
		{
			$data = unserialize($favorite);
			if (!is_array($data))
			{
				$data = array();
			}
		
			array_push($data, $order);
			$data = array_unique($data);
			$order = serialize($data);
			$app->setUserState('com_catalogue.favorite', $order);
			
			echo '1';
		}
		
		return false;
	}

}
