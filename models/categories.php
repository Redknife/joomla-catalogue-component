<?php


defined('_JEXEC') or die;


class CatalogueModelCategories extends JModelList
{

	// public $_context = 'com_catalogue.categories';

	// protected $_extension = 'com_catalogue';

	// private $_parent = null;

	// private $_items = null;



	public function getItems()
	{
		$options = array();

		$this->_items = array();

		$categories = JCategories::getInstance('Catalogue', $options);
		$root = $categories->get($this->getState('category.id', 'root'));
		$categories = $root->getChildren(true);

		foreach ($categories as $category)
		{
			if (!$category->hasChildren())
				$this->_items[] = $category;
		}

		jimport('joomla.utilities.arrayhelper');
		$ids = JArrayHelper::getColumn($this->_items, 'id');

		$query = $this->_db->getQuery(true);
		$query->select('i.category_id, MAX(i.price) AS max_price, MIN(i.price) AS min_price')
			->from('#__catalogue_item AS i')
			->where('i.price <> 0 AND i.category_id IN ('.implode(',',$ids).')')
			->group('i.category_id');

		$this->_db->setQuery($query);
		$prices = $this->_db->loadAssocList('category_id');

		foreach($this->_items as $item)
		{
			$item->max_price = isset($prices[$item->id]) ? $prices[$item->id]['max_price'] : 0;
			$item->min_price = isset($prices[$item->id]) ? $prices[$item->id]['min_price'] : 0;
		}


		return $this->_items;
	}
}