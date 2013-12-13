<?php

defined('_JEXEC') or die;

class CatalogueModelField extends JModelAdmin
{

	public function getTable($type = 'Fields', $prefix = 'CatalogueTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_catalogue.field', 'field', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}


	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.edit.field.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

		}

		return $data;
	}
	
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		
		$registry = new JRegistry();
		$registry->loadString($item->linked_id);
		
		$item->linked_id = $registry->toArray();
		
		return $item;
	}
	
	public function save($data)
	{
		
		return parent::save($data);
	}

}
