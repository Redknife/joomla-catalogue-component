<?php


defined('_JEXEC') or die;


class CatalogueModelCategory extends JModelList
{
	
	public $_context = 'com_catalogue.category';

	protected $_extension = 'com_catalogue';
	
	protected $_item = null;

	protected $_items = null;

	protected $_siblings = null;

	protected $_children = null;

	protected $_parent = null;


	protected function populateState($ordering = NULL, $direction = NULL)
	{
		$app = JFactory::getApplication('site');
		$pk  = $app->input->getInt('cid');

		$this->setState('category.id', $pk);
		
		// Load the parameters. Merge Global and Menu Item params into new object
		$params = $app->getParams();
		$menuParams = new JRegistry;

		if ($menu = $app->getMenu()->getActive())
		{
			$menuParams->loadString($menu->params);
		}

		$mergedParams = clone $menuParams;
		$mergedParams->merge($params);

		$this->setState('params', $mergedParams);
	
		$user		= JFactory::getUser();
				// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		if ((!$user->authorise('core.edit.state', 'com_content')) &&  (!$user->authorise('core.edit', 'com_content'))){
			// limit to published for people who can't edit or edit.state.
			$this->setState('filter.published', 1);
			// Filter by start and end dates.
			$nullDate = $db->quote($db->getNullDate());
			$nowDate = $db->quote(JFactory::getDate()->toSQL());

			$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')')
				->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
		}
		else
		{
			$this->setState('filter.published', array(0, 1, 2));
		}

		// process show_noauth parameter
		if (!$params->get('show_noauth'))
		{
			$this->setState('filter.access', true);
		}
		else
		{
			$this->setState('filter.access', false);
		}

		// Optional filter text
		$this->setState('list.filter', $app->input->getString('filter-search'));

		// filter.order
		$itemid = $app->input->get('id', 0, 'int') . ':' . $app->input->get('Itemid', 0, 'int');
		$orderCol = $app->getUserStateFromRequest('com_catalogue.category.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'i.ordering';
		}
		$this->setState('list.ordering', $orderCol);

		$listOrder = $app->getUserStateFromRequest('com_catalogue.category.list.' . $itemid . '.filter_order_Dir',
			'filter_order_Dir', '', 'cmd');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}
		$this->setState('list.direction', $listOrder);

		$this->setState('list.start', $app->input->get('limitstart', 0, 'uint'));
		
		$limit = $app->getUserStateFromRequest('com_catalogue.category.list.' . $itemid . '.limit', 'limit', $params->get('display_num'), 'uint');
		
		$this->setState('list.limit', $limit);

		// set the depth of the category query based on parameter
		$showSubcategories = $params->get('show_subcategory_content', '0');

		if ($showSubcategories)
		{
			$this->setState('filter.max_category_levels', $params->get('show_subcategory_content', '1'));
			$this->setState('filter.subcategories', true);
		}

		$this->setState('filter.language', JLanguageMultilang::isEnabled());

		$this->setState('layout', $app->input->getString('layout'));

	}
	
	
	function getItems()
	{
		$limit = $this->getState('list.limit');

		if ($this->_items === null && $category = $this->getCategory())
		{
			
			$model = JModelLegacy::getInstance('Items', 'CatalogueModel', array('ignore_request' => true));
			$model->setState('params', JFactory::getApplication()->getParams());
			$model->setState('filter.category_id', $category->id);
			$model->setState('filter.published', $this->getState('filter.published'));
			$model->setState('filter.access', $this->getState('filter.access'));
			$model->setState('filter.language', $this->getState('filter.language'));
			$model->setState('list.ordering', $this->_buildContentOrderBy());
			$model->setState('list.start', $this->getState('list.start'));
			$model->setState('list.limit', $limit);
			$model->setState('list.direction', $this->getState('list.direction'));
			$model->setState('list.filter', $this->getState('list.filter'));
			// filter.subcategories indicates whether to include articles from subcategories in the list or blog
			$model->setState('filter.subcategories', $this->getState('filter.subcategories'));
			$model->setState('filter.max_category_levels', $this->getState('filter.max_category_levels'));

			if ($limit >= 0)
			{
				$this->_items = $model->getItems();

				if ($this->_items === false)
				{
					$this->setError($model->getError());
				}
			}
			else
			{
				$this->_items = array();
			}

			$this->_pagination = $model->getPagination();
		}
		
		return $this->_items;
	}
	
	public function getCategory()
	{
		if (!is_object($this->_item))
		{
			if ( isset( $this->state->params ) )
			{
				$params = $this->state->params;
				$options = array();
				$options['countItems'] = $params->get('show_cat_num_items', 1) || !$params->get('show_empty_categories_cat', 0);
			}
			else {
				$options['countItems'] = 0;
			}

			$categories = JCategories::getInstance('Catalogue', $options);
			$this->_item = $categories->get($this->getState('category.id', 'root'));

			// Compute selected asset permissions.
			if (is_object($this->_item))
			{
				$user	= JFactory::getUser();
				$asset	= 'com_catalogue.category.'.$this->_item->id;

				// Check general create permission.
				if ($user->authorise('core.create', $asset))
				{
					$this->_item->getParams()->set('access-create', true);
				}

				// TODO: Why aren't we lazy loading the children and siblings?
				$this->_children = $this->_item->getChildren();
				$this->_parent = false;

				if ($this->_item->getParent())
				{
					$this->_parent = $this->_item->getParent();
				}

				$this->_rightsibling = $this->_item->getSibling();
				$this->_leftsibling = $this->_item->getSibling(false);
			}
			else {
				$this->_children = false;
				$this->_parent = false;
			}
		}

		return $this->_item;
	}

	/**
	 * Get the parent category.
	 *
	 * @param   integer  An optional category id. If not supplied, the model state 'category.id' will be used.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 * @since   1.6
	 */
	public function getParent()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_parent;
	}

	/**
	 * Get the left sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 * @since   1.6
	 */
	function &getLeftSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_leftsibling;
	}

	/**
	 * Get the right sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 * @since   1.6
	 */
	function &getRightSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_rightsibling;
	}

	/**
	 * Get the child categories.
	 *
	 * @param   integer  An optional category id. If not supplied, the model state 'category.id' will be used.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 * @since   1.6
	 */
	function &getChildren()
	{

		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		// Order subcategories
		
		if (count($this->_children))
		{
			$params = $this->getState()->get('params');
			if ($params->get('orderby_pri') == 'alpha' || $params->get('orderby_pri') == 'ralpha')
			{
				jimport('joomla.utilities.arrayhelper');
				JArrayHelper::sortObjects($this->_children, 'title', ($params->get('orderby_pri') == 'alpha') ? 1 : -1);
			}
		}

		return $this->_children;
	}
	
	protected function _buildContentOrderBy()
	{
		$app		= JFactory::getApplication('site');
		$db			= $this->getDbo();
		$params		= $this->state->params;
		$itemid		= $app->input->get('cid', 0, 'int') . ':' . $app->input->get('Itemid', 0, 'int');
		$orderCol	= $app->getUserStateFromRequest('com_catalogue.category.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		$orderDirn	= $app->getUserStateFromRequest('com_catalogue.category.list.' . $itemid . '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		$orderby	= ' ';

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = null;
		}

		if (!in_array(strtoupper($orderDirn), array('ASC', 'DESC', '')))
		{
			$orderDirn = 'ASC';
		}

		if ($orderCol && $orderDirn)
		{
			$orderby .= $db->escape($orderCol) . ' ' . $db->escape($orderDirn) . ', ';
		}
		
		return $orderby;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			return null;
		}
		return $this->_pagination;
	}
}