<?php


defined('_JEXEC') or die;


class CatalogueModelItem extends JModelList
{

	public $_context = 'com_catalogue.item';

	protected $_extension = 'com_catalogue';

	private $_parent = null;

	private $_items = null;

	public function getItem()
	{
		$id = JRequest::getVar('id', 0);

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('itm.*, cat.title AS category_name');
		$query->from('#__catalogue_item AS itm');
		$query->join('LEFT', '#__categories AS cat ON cat.id = itm.category_id');
		$query->where('itm.state = 1  && itm.published = 1 && itm.id='.$id);

		$db->setQuery($query);
		$this->_items = $db->loadObject();


		$query = $this->_db->getQuery(true);
		$query->select('i.*')
		->from('#__catalogue_assoc as a')
		->join('LEFT', '#__catalogue_item as i ON i.id = a.assoc_id')
		->where('item_id = '.(int) $this->_items->id)
		->order('ordering ASC');
		$this->_db->setQuery($query);

		$this->_items->assoc = $this->_db->loadObjectList();

		$this->_addToSimilarList($this->_items);

		return $this->_items;
	}

	protected function populateState($ordering = NULL, $direction = NULL)
	{
		$app = JFactory::getApplication('site');

		$offset = $app->input->getUInt('limitstart');
		$this->setState('list.offset', $offset);

		$id = $app->input->getUInt('id');
		$this->setState('item.id', $id);

		$db	= JFactory::getDbo();

		$db->setQuery(
			$db->getQuery(true)
			->select('item_name, item_description, params, metadata')
			->from('#__catalogue_item')
			->where('state = 1 AND published AND id = '.$id)
			);

		$item = $db->loadObject();

		$this->setState('item.name', $item->item_name);
		$this->setState('item.params', $item->params);
		$this->setState('item.metadata', $item->metadata);
		$this->setState('item.desc', $item->item_description);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

	}

	private function _addToSimilarList($item)
	{
		$app = JFactory::getApplication();
		$params = $app->getParams();

		$ctn = 3;
		$id = $item->id;
		$query	= $this->_db->getQuery(true);
		$query->select('itm.*');
		$query->from('#__catalogue_item AS itm');
		$query->where('itm.state = 1')
				->where('itm.published = 1', 'AND')
				->where('itm.id <> '.$id, 'AND')
				->where('itm.category_id = '.$item->category_id, 'AND')
				->order('itm.price DESC');
		$this->_db->setQuery($query);
		$similar = $this->_db->loadAssocList('id');

		// var_dump($similar);
		// die;

		$count_similar = count($similar);
		$similar_items = array();

		$num = 1;
		$direct = 0;
		$i = 1;
		if($count_similar > $ctn){
			while (count($similar_items)!=$ctn) {
				$direct == 0 ? $cur = @$similar[$id-$num] : $cur = @$similar[$id+$num];
				if($cur != NULL) $similar_items[] = $cur;
				$i%2 ? $num++ : $num;
				$direct == 0 ? $direct = 1 : $direct = 0;
				$i++;
			}
		}
		else{
			$similar_items = $similar;
		}

		$list = serialize($similar_items);
		$app->setUserState('com_catalogue.similaritems', $list);
	}
}