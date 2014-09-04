<?php
defined('_JEXEC') or die;
class CatalogueRouter extends JComponentRouterBase
{
	/**
	 * Build the route for the com_catalogue component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query)
	{
		$segments = array();

		// Get a menu item based on Itemid or currently active
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$params = JComponentHelper::getParams('com_catalogue');
		$advanced = $params->get('sef_advanced_link', 0);
		$add_id_alias = (int)$params->get('add_id_alias', 1);

		// We need a menu item.  Either the one specified in the query, or the current active one if none specified
		if (empty($query['Itemid']))
		{
			$menuItem = $menu->getActive();
			$menuItemGiven = false;
		}
		else
		{
			$menuItem = $menu->getItem($query['Itemid']);
			$menuItemGiven = true;
		}

		// Check again
		if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_catalogue')
		{
			$menuItemGiven = false;
			unset($query['Itemid']);
		}

		if (isset($query['view']))
		{
			$view = $query['view'];
		}
		else
		{
			// We need to have a view in the query or it is an invalid URL
			return $segments;
		}

		// Are we dealing with an item or category that is attached to a menu item?
		if (($menuItem instanceof stdClass) && $menuItem->query['view'] == $query['view'] && isset($query['id']) && $menuItem->query['id'] == (int) $query['id'])
		{
			unset($query['view']);

			if (isset($query['cid']))
			{
				unset($query['cid']);
			}

			if (isset($query['layout']))
			{
				unset($query['layout']);
			}

			unset($query['id']);

			return $segments;
		}

		if ($view == 'category' || $view == 'item')
		{
			if (!$menuItemGiven)
			{
				$segments[] = $view;
			}

			unset($query['view']);

			if ($view == 'item')
			{
				if (isset($query['id']) && isset($query['cid']) && $query['cid'])
				{
					$catid = $query['cid'];

					// Make sure we have the id and the alias
					if (strpos($query['id'], ':') === false)
					{
						$db = JFactory::getDbo();
						$dbQuery = $db->getQuery(true)
							->select('alias')
							->from('#__catalogue_item')
							->where('id=' . (int) $query['id']);
						$db->setQuery($dbQuery);
						$alias = $db->loadResult();

						if ($add_id_alias)
							$query['id'] = $query['id'] . ':' . $alias;
						else
							$query['id'] = $alias;
					}


				}
				else
				{
					// We should have these two set for this view.  If we don't, it is an error
					return $segments;
				}
			}
			else
			{
				if (isset($query['cid']))
				{
					$catid = $query['cid'];
				}
				else
				{
					// We should have id set for this view.  If we don't, it is an error
					return $segments;
				}
			}

			if ($menuItemGiven && isset($menuItem->query['cid']))
			{
				$mCatid = $menuItem->query['cid'];
			}
			else
			{
				$mCatid = 0;
			}

			$categories = JCategories::getInstance('Catalogue');
			$category = $categories->get($catid);

			if (!$category)
			{
				// We couldn't find the category we were given.  Bail.
				return $segments;
			}

			$path = array_reverse($category->getPath());

			$array = array();

			foreach ($path as $id)
			{
				if ((int) $id == (int) $mCatid)
				{
					break;
				}

				list($tmp, $id) = explode(':', $id, 2);

				$array[] = $id;
			}

			$array = array_reverse($array);

			if (!$advanced && count($array))
			{
				$array[0] = (int) $catid . ':' . $array[0];
			}

			$segments = array_merge($segments, $array);

			if ($view == 'item')
			{
				if ($advanced)
				{
					list($tmp, $id) = explode(':', $query['id'], 2);
				}
				else
				{
					$id = $query['id'];
				}

				$segments[] = $id;
			}

			unset($query['id']);
			unset($query['cid']);
		}

		if ($view == 'archive')
		{
			if (!$menuItemGiven)
			{
				$segments[] = $view;
				unset($query['view']);
			}

			if (isset($query['year']))
			{
				if ($menuItemGiven)
				{
					$segments[] = $query['year'];
					unset($query['year']);
				}
			}

			if (isset($query['year']) && isset($query['month']))
			{
				if ($menuItemGiven)
				{
					$segments[] = $query['month'];
					unset($query['month']);
				}
			}
		}

		if ($view == 'featured')
		{
			if (!$menuItemGiven)
			{
				$segments[] = $view;
			}

			unset($query['view']);
		}

		/*
		 * If the layout is specified and it is the same as the layout in the menu item, we
		 * unset it so it doesn't go into the query string.
		 */
		if (isset($query['layout']))
		{
			if ($menuItemGiven && isset($menuItem->query['layout']))
			{
				if ($query['layout'] == $menuItem->query['layout'])
				{
					unset($query['layout']);
				}
			}
			else
			{
				if ($query['layout'] == 'default')
				{
					unset($query['layout']);
				}
			}
		}

		$total = count($segments);

		for ($i = 0; $i < $total; $i++)
		{
			$segments[$i] = str_replace(':', '-', $segments[$i]);
		}

		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments)
	{
		$total = count($segments);
		$vars = array();

		for ($i = 0; $i < $total; $i++)
		{
			if (preg_match('/^\d+-/', $segments[$i]))
				$segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);

		}

		// Get the active menu item.
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();
		$params = JComponentHelper::getParams('com_catalogue');
		$advanced = $params->get('sef_advanced_link', 0);
		$db = JFactory::getDbo();

		// Count route segments
		$count = count($segments);

		/*
		 * Standard routing for items.  If we don't pick up an Itemid then we get the view from the segments
		 * the first segment is the view and the last segment is the id of the item or category.
		 */
		if (!isset($item))
		{
			$vars['view'] = $segments[0];
			$vars['id'] = $segments[$count - 1];

			return $vars;
		}

		/*
		 * If there is only one segment, then it points to either an item or a category.
		 * We test it first to see if it is a category.  If the id and alias match a category,
		 * then we assume it is a category.  If they don't we assume it is an item
		 */
		if ($count == 1)
		{
			// We check to see if an alias is given.  If not, we assume it is an item
			if (strpos($segments[0], ':') === false)
			{
				$vars['view'] = 'item';
				$vars['id'] = (int) $segments[0];


				// add alias to query array for build
				if (!$vars['id'])
				{

					$db = JFactory::getDbo();
					$dbQuery = $db->getQuery(true)
						->select('id')
						->from('#__catalogue_item')
						->where('alias=' . $db->quote($segments[0]));
					$db->setQuery($dbQuery);

					$id = $db->loadResult();

					$segments[0] = $id.':'.$segments[0];
				}

			}
			else
			{
				list($id, $alias) = explode(':', $segments[0], 2);
				$db = JFactory::getDbo();
				$dbQuery = $db->getQuery(true)
					->select('alias')
					->from('#__catalogue_item')
					->where('id=' . $db->quote((int)$id));
				$db->setQuery($dbQuery);

				$alias = $db->loadResult();
				if(!$alias){
					JError::raiseError(404, JText::_('COM_CATALOGUE_ERROR_ITEM_NOT_FOUND'));
					return $vars;
				}
			}

			list($id, $alias) = explode(':', $segments[0], 2);

			// First we check if it is a category
			$category = JCategories::getInstance('Catalogue')->get($id);

			if ($category && $category->alias == $alias)
			{
				$vars['view'] = 'category';
				$vars['id'] = $id;

				return $vars;
			}
			else
			{
				$query = $db->getQuery(true)
					->select($db->quoteName(array('alias', 'category_id')))
					->from($db->quoteName('#__catalogue_item'))
					->where($db->quoteName('id') . ' = ' . (int) $id);
				$db->setQuery($query);
				$item = $db->loadObject();

				if ($item)
				{
					if ($item->alias == $alias)
					{
						$vars['view'] = 'item';
						$vars['cid'] = (int) $item->category_id;
						$vars['id'] = (int) $id;

						return $vars;
					}

				}
				else
				{
					JError::raiseError(404, JText::_('COM_CATALOGUE_ERROR_ITEM_NOT_FOUND'));
					return $vars;
				}
			}
		}

		/*
		 * If there was more than one segment, then we can determine where the URL points to
		 * because the first segment will have the target category id prepended to it.  If the
		 * last segment has a number prepended, it is an item, otherwise, it is a category.
		 */
		if (!$advanced)
		{
			$cat_id = (int) $segments[0];

			$item_id = (int) $segments[$count - 1];

			if ($item_id > 0)
			{
				$vars['view'] = 'item';
				$vars['cid'] = $cat_id;
				$vars['id'] = $item_id;
			}
			else
			{
				$vars['view'] = 'category';
				$vars['id'] = $cat_id;
			}

			return $vars;
		}

		// We get the category id from the menu item and search from there
		$id = $item->query['id'];
		$category = JCategories::getInstance('Catalogue')->get($id);

		if (!$category)
		{
			JError::raiseError(404, JText::_('COM_CATALOGUE_ERROR_PARENT_CATEGORY_NOT_FOUND'));
			return $vars;
		}

		$categories = $category->getChildren();
		$vars['cid'] = $id;
		$vars['id'] = $id;
		$found = 0;

		foreach ($segments as $segment)
		{
			$segment = str_replace(':', '-', $segment);

			foreach ($categories as $category)
			{
				if ($category->alias == $segment)
				{
					$vars['id'] = $category->id;
					$vars['cid'] = $category->id;
					$vars['view'] = 'category';
					$categories = $category->getChildren();
					$found = 1;
					break;
				}
			}

			if ($found == 0)
			{
				if ($advanced)
				{
					$db = JFactory::getDbo();
					$query = $db->getQuery(true)
						->select($db->quoteName('id'))
						->from('#__catalogue_item')
						->where($db->quoteName('category_id') . ' = ' . (int) $vars['cid'])
						->where($db->quoteName('alias') . ' = ' . $db->quote($db->quote($segment)));
					$db->setQuery($query);
					$cid = $db->loadResult();
				}
				else
				{
					$cid = $segment;
				}

				$vars['id'] = $cid;

				if ($item->query['view'] == 'archive' && $count != 1)
				{
					$vars['year'] = $count >= 2 ? $segments[$count - 2] : null;
					$vars['month'] = $segments[$count - 1];
					$vars['view'] = 'archive';
				}
				else
				{
					$vars['view'] = 'item';
				}
			}

			$found = 0;
		}

		return $vars;
	}
}

/**
 * Catalogue router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function CatalogueBuildRoute(&$query)
{
	$router = new CatalogueRouter;

	return $router->build($query);
}

function CatalogueParseRoute($segments)
{
	$router = new CatalogueRouter;

	return $router->parse($segments);
}