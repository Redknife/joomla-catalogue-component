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
    $mSid	= (empty($menuItem->query['cid'])) ? null : $menuItem->query['sid'];
    $mSsid	= (empty($menuItem->query['cid'])) ? null : $menuItem->query['ssid'];
	//$mId	= (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];
	

	if (isset($query['view']))
	{
		$view = $query['view'];
		if (empty($query['Itemid']) || empty($menuItem) || $menuItem->component != 'com_catalogue')
		{
			$segments[] = $query['view'];
		}
		unset($query['view']);
	};
	

	if (isset($view) && ($mView == $view) && (isset($query['id'])) )
	{
		unset($query['view']);
		unset($query['cid']);
		return $segments;
	}
    
    
    if (isset($query['ssid']))
	{
		
		$db = JFactory::getDbo();
		$aquery = $db->setQuery(
			$db->getQuery(true)
				->select('alias')
				->from('#__catalogue_supersection')
				->where('id=' . (int) $query['ssid'])
		);
		$alias = $db->loadResult();
		$segments[] = $query['ssid'].':'.$alias;
		unset($query['ssid']);
		
	}
    
    if (isset($query['sid']))
	{
		
		$db = JFactory::getDbo();
		$aquery = $db->setQuery(
			$db->getQuery(true)
				->select('alias')
				->from('#__catalogue_section')
				->where('id=' . (int) $query['sid'])
		);
		$alias = $db->loadResult();
		$segments[] = $query['sid'].':'.$alias;
		unset($query['sid']);
		
	}
	
	if (isset($query['cid']))
	{
		
		$db = JFactory::getDbo();
		$aquery = $db->setQuery(
			$db->getQuery(true)
				->select('alias')
				->from('#__catalogue_category')
				->where('id=' . (int) $query['cid'])
		);
		$alias = $db->loadResult();
		$segments[] = $query['cid'].':'.$alias;
		unset($query['cid']);
		
	}
  
	if (isset($query['ssid'])  && isset($query['sid']) && $view == 'section')
	{
		unset($query['ssid']);
        unset($query['sid']);
	}
	
    if (isset($query['ssid'])  && isset($query['sid']) && isset($query['cid']) && $view == 'items')
	{
		unset($query['ssid']);
        unset($query['sid']);
        unset($query['cid']);
	}
    //var_dump($segments);
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
    
	switch ($count)
	{
        case 2:
			$vars['view'] = 'section';
            $ssid = explode(':',$segments[0]);
            $sid = explode(':',$segments[1]);
            $vars['ssid'] = $ssid[0];
			$vars['sid'] = $sid[0];
		break;
      
		case 3:
			$vars['view'] = 'items';
            $cid = explode(':',$segments[2]);
			$vars['cid'] = $cid[0];
		break;
	}
	return $vars;
}

	