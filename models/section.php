<?php
defined('_JEXEC') or die;

class CatalogueModelSection extends JModelList
{
	
	public $_context = 'com_catalogue.sections';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'sec.id',
				'section_name', 'sec.section_name',
				'alias', 'sec.alias',
				'state', 'sec.state',
				'ordering', 'sec.ordering',
				'published', 'sec.published'
			);
		}
		parent::__construct($config);
	}

	protected function populateState($ordering = NULL, $direction = NULL)
	{
		$app = JFactory::getApplication('site');

		$ssid = $app->input->getUInt('ssid');
		$this->setState('supersection.id', $ssid);
		
		$sid = $app->input->getUInt('sid');
		$this->setState('section.id', $sid);
		
		$db	= JFactory::getDbo();
		
		$db->setQuery(
			$db->getQuery(true)
			->select('supersection_name, supersection_description')
			->from('#__catalogue_supersection')
			->where('state = 1 AND published = 1 AND id = '.$ssid)
		);
		
		$supersection = $db->loadObject();
		
		$this->setState('supersection.name', $supersection->supersection_name);
		$this->setState('supersection.desc', $supersection->supersection_description);
		
		$db->setQuery(
			$db->getQuery(true)
			->select('section_name, section_description')
			->from('#__catalogue_section')
			->where('state = 1 AND published = 1 AND id = '.$sid)
		);
		
		$section = $db->loadObject();
		
		$this->setState('section.name', $section->section_name);
		$this->setState('section.desc', $section->section_description);
		
		$params = $app->getParams();
		$this->setState('params', $params);
		
		parent::populateState('ordering', 'ASC');
	}

	public function getItems()
	{
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
        //$app = JFactory::getApplication('site');
        //$secid = $app->getUserState('section.id');
        $secid = JRequest::getVar('sid', 0);

		$query->select('sec.section_name, cat.category_name, cat.id');
		$query->from('#__catalogue_category AS cat');
		$query->join('LEFT', '#__catalogue_section as sec ON sec.id ='.$secid);
		$query->where('cat.state = 1 AND cat.published = 1 AND cat.section_id ='.$secid);
		$query->order('cat.category_name');
		$db->setQuery($query);
		$this->_items = $db->loadObjectList();

		return $this->_items;
	}

}