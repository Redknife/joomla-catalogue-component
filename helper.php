<?php
defined('_JEXEC') or die;

require_once('thumbnail.php');

class CatalogueHelper {
	
	static function getCartItems()
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.cart');

		if ($data)
		{
			$cart_items = unserialize($data);
			if (!empty($cart_items))
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				
				$query->select('*');
				$query->from('#__catalogue_item');
				$query->where('id IN ('.implode(',', $cart_items).')');
                $query->order('item_name');
				$db->setQuery($query);
				$items = $db->loadObjectList();
                return $items;
			}
		}
		return false;
	}
	
	static function getWatchListItems()
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.watchlist');
		if ($data)
		{
			$watchlist = unserialize($data);
			$watchlist = array_filter($watchlist);
			if (!empty($watchlist))
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				
				$query->select('*');
				$query->from('#__catalogue_item');
				
				$query->where('id IN ('.implode(',', $watchlist).')');
				$query->order('FIELD(id, '.implode(',', $watchlist).')');
			
				$db->setQuery($query, 1, 8);
				$items = $db->loadObjectList();
			}
			return $items;
		}
		
		return false;
	}
	
	static function getFavoriteItems()
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.favorite');
		if ($data)
		{
			$favorite = unserialize($data);
			$favorite = array_filter($favorite);
			if (!empty($favorite))
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				
				$query->select('*');
				$query->from('#__catalogue_items');
				
				$query->where('id IN ('.implode(',', $favorite).')');
				$query->order('FIELD(id, '.implode(',', $favorite).')');
			
				$db->setQuery($query);
				$items = $db->loadObjectList();
				
			}
			return $items;
		}
		return false;
	}
	
	static function getItemsByIds($ids)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('*');
		$query->from('#__catalogue_items');
		$query->where('id IN ('.implode(',', $ids).')');
	
		$db->setQuery($query);
		$items = $db->loadObjectList();	
		
		return $items;
	}
	
	static function getItemById($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('*');
		$query->from('#__catalogue_item');
		$query->where('id = '.$id);
		$db->setQuery($query);
		$item = $db->loadObject();	
		
		return $item;
	}

	static function inCart($id)
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.cart');
		if ($data)
		{
			$cart_items = unserialize($data);
            return in_array($id, $cart_items);
		}
		return false;
	}
	
    static function getCount($id)
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.cart');
		if ($data)
		{
			$cart = unserialize($data);
            $ids = array_keys($cart);
            if( in_array($id, $ids) )
              return $cart[$id];
            return 1;
		}
		return 1;
	}
    
	static function inWatchList($id)
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.watchlist');
		
		if ($data)
		{
			$watchlist = (array) unserialize($data);
			return in_array($id, $watchlist);
		}
		return false;
	}
	
	static function isFavorite($id)
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.favorite');
		if ($data)
		{
			$favorite = unserialize($data);
			return in_array($id, $favorite);
		}
		return false;
	}
	
	static function getCartInfo()
	{
		$summ = 0;
		$items = self::getCartItems();
		if ($items) foreach ($items as $item)
		{
			$summ += $item->price;
		}
		return array('count' => $items ? count($items) : 0, 'summ' => $summ);
	}

	
	static function createThumb($id, $image, $width, $height, $return = 'min')
	{
		
		$rel_path = dirname($image);
		$path = JPATH_BASE.DS.$rel_path;

		$path_min = $path.DS.$id.DS.'min';
		$path_mid = $path.DS.$id.DS.'mid';
		
		$absolute_image = $path.DS.basename($image);
		
		if (!file_exists($absolute_image))
		{
			return false;
		}
		
		if (!file_exists($path.DS.$id))
		{
			mkdir($path.DS.$id, 0777);	
		}

		if (!file_exists($path_min))
		{
			mkdir($path_min, 0777);	
		}
		
		if (!file_exists($path_mid))
		{
			mkdir($path_mid, 0777);	
		}
		
		
		$minOptions = array(
				'width' => 440,
				'height' => 440,
				'method' => THUMBNAIL_METHOD_SCALE_MIN,
			);
		
		$midOptions = array(
				'width' => $width,
				'height' => $height,
				'method' => THUMBNAIL_METHOD_SCALE_MIN,
			);
			
		
		$p['min'] = str_replace('.jpg', '-mid.jpg', $path_mid.DS.basename($image));
		$p['mid'] = str_replace('.jpg', '-min.jpg', $path_min.DS.basename($image));
		
		if (!file_exists($p['min']))
		{
			$minImage = Thumbnail::render($image, $minOptions);
			@imageJpeg($minImage, $p['min'], 80);
			@imagedestroy($minImage);
		}

		if (!file_exists($p['mid']))
		{
			$midImage = Thumbnail::render($image, $midOptions);
			@imageJpeg($midImage, $p['mid'], 100);
			@imagedestroy($midImage);
		}
		
		
		
		return $rel_path.'/'.$id.'/'.$return.'/'.str_replace('.jpg', '-'.$return.'.jpg', basename($image));
	}

}


