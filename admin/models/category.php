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
		$item = parent::getItem($pk);
		
		$db = JFactory::getDbo();
		
		// Display stored skin types, effects and componens while editing item
		if ($item->id)
		{
			$query_effect = $db->getQuery(true);
			$query_effect->select('*')->from('#__catalogue_links')->where('ptype = 2 AND parent = '.$item->id);
			$db->setQuery($query_effect);
			$result = $db->loadObjectList();
			$effects = array();
			foreach($result as $row)
			{
				$effects[] = $row->parent;
			}
			$item->child_item = $effects;
			//print_r($effects);
			/*
			//
			$query_skin = $db->getQuery(true);
			$query_skin->select('skin_id')->from('#__catalogue_item_skin')->where('item_id = '.$item->id);
			$db->setQuery($query_skin);
			$result = $db->loadObjectList();
			$skins = array();
			foreach($result as $row)
			{
				$skins[] = $row->skin_id;
			}
			$item->skin_id = $skins;
			
			//
			$query_component = $db->getQuery(true);
			$query_component->select('component_id')->from('#__catalogue_item_component')->where('item_id = '.$item->id);
			$db->setQuery($query_component);
			$result = $db->loadObjectList();
			$components = array();
			foreach($result as $row)
			{
				$components[] = $row->component_id;
			}
			$item->component_id = $components;
			*/
			
		}
		
		return $item;
		
	
		
	}
	
	public function save($data)
	{
		$linked_ids = $data['linked_id'];
		$registry = new JRegistry($linked_ids);
		$data['linked_id'] = $registry->toString();
		
		if (isset($data['alias']) && empty($data['alias'])){
			$data['alias'] = ru_RULocalise::transliterate($data['category_name']);
			$data['alias'] =  preg_replace('#\W#', '-', $data['alias']);
			$data['alias'] =  preg_replace('#[-]+#', '-', $data['alias']);
        }
		
		return parent::save($data);
	}

}
