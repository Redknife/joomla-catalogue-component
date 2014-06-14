<?php

defined('_JEXEC') or die;

class CatalogueModelCategory extends JModelAdmin
{

	public function getTable($type = 'Categories', $prefix = 'CatalogueTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_catalogue.category', 'category', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}


	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.edit.category.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('catalogue.id') == 0)
			{
				// save filter for new category	
				// $data->set('catid', $app->input->getInt('catid', $app->getUserState('com_catalogue.category.filter.cat_id')));
			}
		}

		return $data;
	}
	
	public function getItem($pk = null)
	{
		if ($result = parent::getItem($pk))
		{
		
			// Convert the metadata field to an array.
			$metadata = new JRegistry;
			$metadata->loadString($result->metadata);
			$result->metadata = $metadata->toArray();
			
	
		}
		return $result;
		
	
		
	}
	
	public function save($data)
	{
		
		// Convert the metadata array to string.
		$metadata = new JRegistry($data['metadata']);
		$data['metadata'] = $metadata->toString();
		
		// Convert the params array to string.
		$params = new JRegistry($data['params']);
		$data['params'] = $params->toString();
		
		if (isset($data['alias']) && empty($data['alias'])){
			$data['alias'] = ru_RULocalise::transliterate($data['category_name']);
			$data['alias'] =  preg_replace('#\W#', '-', $data['alias']);
			$data['alias'] =  preg_replace('#[-]+#', '-', $data['alias']);
        }
		
		return parent::save($data);
	}

}
