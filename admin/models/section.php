<?php

defined('_JEXEC') or die;

class CatalogueModelSection extends JModelAdmin
{

	public function getTable($type = 'Sections', $prefix = 'CatalogueTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_catalogue.section', 'section', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}


	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.edit.section.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
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
		if (isset($data['alias']) && empty($data['alias'])){
			$data['alias'] = ru_RULocalise::transliterate($data['section_name']);
			$data['alias'] =  preg_replace('#\W#', '-', $data['alias']);
			$data['alias'] =  preg_replace('#[-]+#', '-', $data['alias']);
        }
				
		return parent::save($data);
	}
	
}
