<?php
defined('_JEXEC') or die;


class CatalogueHelper
{
	
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_CATALOGUE_SUBMENU_CATALOGUE'),
			'index.php?option=com_catalogue&view=catalogue',
			$vName == 'catalogue'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_CATALOGUE_SUBMENU_CATEGORIES'),
			'index.php?option=com_catalogue&view=categories',
			$vName == 'categories'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_CATALOGUE_SUBMENU_SECTIONS'),
			'index.php?option=com_catalogue&view=sections',
			$vName == 'sections'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_CATALOGUE_SUBMENU_SUPERSECTIONS'),
			'index.php?option=com_catalogue&view=supersections',
			$vName == 'supersections'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_CATALOGUE_SUBMENU_MANUFACTURERS'),
			'index.php?option=com_catalogue&view=manufacturers',
			$vName == 'manufacturers'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_CATALOGUE_SUBMENU_COUNTRIES'),
			'index.php?option=com_catalogue&view=countries',
			$vName == 'countries'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_CATALOGUE_SUBMENU_FORMS'),
			'index.php?option=com_catalogue&view=forms',
			$vName == 'forms'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_CATALOGUE_SUBMENU_FIELDS'),
			'index.php?option=com_catalogue&view=fields',
			$vName == 'fields'
		);		
	}


	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = JAccess::getActions('com_catalogue', 'component');

		foreach ($actions as $action) {
			$result->set($action->name,	$user->authorise($action->name, 'com_catalogue'));
		}
		
		return $result;
	}
	
	public static function getCategoriesOptions()
	{
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id As value, category_name As text');
		$query->from('#__catalogue_category AS cat');
		$query->order('cat.category_name');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		// Merge any additional options in the XML definition.
		//$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
	
	public static function getSectionsOptions()
	{
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id As value, section_name As text');
		$query->from('#__catalogue_section AS sec');
		$query->order('sec.section_name');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}
	
	public static function getFormsOptions()
	{
		$options = array();

		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id As value, form_name As text');
		$query->from('#__catalogue_form AS f');
		$query->order('f.form_name');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}
	
	public static function getItemsOptions($exclude = false)
	{
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id As value, item_name As text');
		$query->from('#__catalogue_item AS i');
		$query->order('i.item_name');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}

	public static function getCountriesOptions()
	{
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id As value, country_name As text');
		$query->from('#__catalogue_country AS ctr');
		$query->order('ctr.country_name');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}
	
	public static function getManufacturersOptions()
	{
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id As value, manufacturer_name As text');
		$query->from('#__catalogue_manufacturer AS ctr');
		$query->order('ctr.manufacturer_name');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}
	
	public static function getSupersectionsOptions()
	{
		$options = array();

		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id As value, supersection_name As text');
		$query->from('#__catalogue_supersection AS ssec');
		$query->order('ssec.supersection_name');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}
}
