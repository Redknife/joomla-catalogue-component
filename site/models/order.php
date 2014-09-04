<?php

defined('_JEXEC') or die;

class CatalogueModelOrder extends JModelAdmin
{


	public function getTable($type = 'Order', $prefix = 'CatalogueTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_catalogue.order', 'order', array('control' => 'jform', 'load_data' => $loadData));
                
		if (empty($form)) {
			return false;
		}
                
		return $form;
	}	

}
