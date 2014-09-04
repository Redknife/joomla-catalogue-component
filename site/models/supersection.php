<?php
defined('_JEXEC') or die;

class CatalogueModelSupersection extends JModelList
{
	
	public $_context = 'com_catalogue.supersection';
	protected $_extension = 'com_catalogue';
	private $_parent = null;
	private $_items = null;

	protected function populateState($ordering = NULL, $direction = NULL)
	{
		$app = JFactory::getApplication();

		$ssid = $app->input->getUInt('ssid');
		$this->setState('supersection.id', $ssid);

		$db	= JFactory::getDbo();
		$db->setQuery(
			$db->getQuery(true)
			->select('supersection_name, supersection_description, params, metadata')
			->from('#__catalogue_supersection')
			->where('state = 1 AND published = 1 AND id = '.$ssid)
		);
		
		$supersection = $db->loadObject();
		
		$this->setState('supersection.name', $supersection->supersection_name);
		$this->setState('supersection.description', $supersection->supersection_description);
		$this->setState('supersection.params', $supersection->params);
		$this->setState('supersection.metadata', $supersection->metadata);
		
		$params = $app->getParams();
		$this->setState('params', $params);
		
		parent::populateState('ordering', 'ASC');
	}

	public function getListQuery()
	{	
        $app = JFactory::getApplication();
		$params = $app->getParams()->toArray();
	    $ordering = (int)$params['catalogue_sort'];
	    $ssid = $this->state->get('supersection.id');

		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('ssec.*, sec.*');
		$query->from('#__catalogue_section AS sec');
		$query->join('LEFT', '#__catalogue_supersection AS ssec ON ssec.id = '.$ssid);
		$query->where('sec.state = 1 AND sec.published = 1 AND sec.supersection_id = '.$ssid);
		if($ordering == 2){
			$query->order('sec.section_name');	
		}
		else{
			$query->order('sec.ordering');
		}
		$db->setQuery($query);

		return $query;
	}

}