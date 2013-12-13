<?php

defined('_JEXEC') or die;

class CatalogueTableCatalogue extends JTable
{
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_item', 'id', $_db);
	}
	
	public function store($updateNulls = false)
	{
		$result = parent::store($updateNulls);
		
		$keyName = $this->getKeyName();
		$this->load($this->$keyName);
		
		$jform = JFactory::getApplication()->input->post->get('jform', array(), 'array');
		
		return $result;
	}
	
}