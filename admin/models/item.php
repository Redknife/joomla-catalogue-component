<?php

defined('_JEXEC') or die;

class CatalogueModelItem extends JModelAdmin
{


	public function getTable($type = 'Catalogue', $prefix = 'CatalogueTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_catalogue.item', 'item', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}


	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.edit.item.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('item.id') == 0)
			{
				$data->set('cat_id', $app->input->getInt('catid', $app->getUserState('com_catalogue.catalogue.filter.cat_id')));
			}
		}
		return $data;
	}
	
	public function getItem($pk = null)
	{
		
		return parent::getItem($pk);
	}
	
	public function save($data)
	{
		/*
		$params = array_map(array($this, '_restructData'), $data['params']['name'], $data['params']['price'], $data['params']['count']);
		$registry = new JRegistry($params);
		$data['params'] = $registry->toString();
		*/
		
		if (isset($data['alias']) && empty($data['alias'])){
			$data['alias'] = ru_RULocalise::transliterate($data['item_name']);
			$data['alias'] =  preg_replace('#\W#', '-', $data['alias']);
			$data['alias'] =  preg_replace('#[-]+#', '-', $data['alias']);
        }
		
		return parent::save($data);
	}
	
	protected function _restructData($name, $price, $count)
	{
		if ($name && $price && $count)
			return array('name' => $name, 'price' => $price, 'count' => $count);
		
	}
	
	
	
}
