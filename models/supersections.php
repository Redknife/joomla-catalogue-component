<?php
defined('_JEXEC') or die;

class CatalogueModelSupersections extends JModelList
{
	
	public $_context = 'com_catalogue.supersections';
	protected $_extension = 'com_catalogue';
	private $_parent = null;
	private $_items = null;

	protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
		$app = JFactory::getApplication();

		// List state information
		// $value = $app->input->get('limit', $app->getCfg('list_limit', 0), 'uint');
		// $this->setState('list.limit', $value);

		// $value = $app->input->get('limitstart', 0, 'uint');
		// $this->setState('list.start', $value);

		$params = $app->getParams();
		$this->setState('params', $params);
	}

	public function getListQuery()
	{	
        $app = JFactory::getApplication();
		$params = $app->getParams()->toArray();
	    $ordering = (int)$params['catalogue_sort'];

		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('ssec.*');
		$query->from('#__catalogue_supersection AS ssec');
		$query->where('ssec.state = 1 AND ssec.published = 1');
		if($ordering == 2){
			$query->order('ssec.supersection_name');	
		}
		else{
			$query->order('ssec.ordering');
		}
		$db->setQuery($query);

		return $query;
	}

}