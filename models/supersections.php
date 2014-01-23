<?php


defined('_JEXEC') or die;


class CatalogueModelSupersections extends JModelList
{
	
	public $_context = 'com_catalogue.supersections';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'ssec.id',
				'supersection_name', 'ssec.supersection_name',
				'alias', 'ssec.alias',
				'state', 'ssec.state',
				'ordering', 'ssec.ordering',
				'published', 'ssec.published'
			);
		}
		parent::__construct($config);
	}

	public function getItems()
	{
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('ssec.supersection_name, ssec.id, sec.section_name, sec.id as sectionid');
		$query->from('#__catalogue_supersection AS ssec');
		$query->join('LEFT', '#__catalogue_section as sec ON sec.supersection_id = ssec.id');
		$query->where('ssec.state = 1 AND ssec.published = 1');
		$query->order('ssec.supersection_name');
		$db->setQuery($query);
		$this->_items = $db->loadObjectList();

		return $this->_items;
	}

}