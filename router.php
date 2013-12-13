<?php


defined('_JEXEC') or die;


function CatalogueBuildRoute(&$query)
{
	$segments = array();	
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();

	if (empty($query['Itemid']))
	{
		$menuItem = $menu->getActive();
	}
	else
	{
		$menuItem = $menu->getItem($query['Itemid']);
	}
	
	$mView	= (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mCid	= (empty($menuItem->query['cid'])) ? null : $menuItem->query['cid'];
	$mId	= (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];
	

	if (isset($query['view']))
	{
		$view = $query['view'];
		if (empty($query['Itemid']) || empty($menuItem) || $menuItem->component != 'com_catalogue')
		{
			$segments[] = $query['view'];
		}
		unset($query['view']);
	};
	

	if (isset($view) && ($mView == $view) and (isset($query['id'])) and ($mId == (int) $query['id']))
	{
		unset($query['view']);
		unset($query['cid']);
		unset($query['id']);
		return $segments;
	}
	
	if (isset($query['cid']))
	{
		
		$db = JFactory::getDbo();
		$aquery = $db->setQuery(
			$db->getQuery(true)
				->select('alias')
				->from('#__catalogue_categories')
				->where('id=' . (int) $query['cid'])
		);
		$alias = $db->loadResult();
		$segments[] = $query['cid'].':'.$alias;
		unset($query['cid']);
		
	}

	if (isset($query['cid'])  && isset($query['id']) && $view == 'category')
	{
		unset($query['id']);
	}
	
	return $segments;
}

function CatalogueParseRoute($segments)
{
	
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	
	if (empty($query['Itemid']))
	{
		$menuItem = $menu->getActive();
	}
	else
	{
		$menuItem = $menu->getItem($query['Itemid']);
	}
	
	$count = count($segments);
	$id = JRequest::getVar('id',0);
	
	switch ($menuItem->query['view'])
	{
		case 'categories' :
			if (!$id)
				$vars['view'] = 'category';
			else
				$vars['view'] = 'item';
			if ($count)
			{
				$cid = explode(':',$segments[0]);
			}
			$vars['cid'] = $cid[0];
		break;
	}
	
	return $vars;
}

	