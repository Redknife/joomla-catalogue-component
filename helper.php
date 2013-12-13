<?php
defined('_JEXEC') or die;

require_once('thumbnail.php');

class CatalogueHelper {
	
	static function getCart(){
		
		$lastId = JRequest::getVar('orderId');
			
		$cart_list = self::getCartItems();
		
		$result['count'] = count($cart_list);
		$result['summ'] = 0;
		
		if ($cart_list) foreach ($cart_list as $item){
			$result['summ'] += $item->price;
			if ($item->id == $lastId)
				$lastItem = $item;
		}
		
		$result['lastItem'] = array('title' => $lastItem->name, 'price' => $lastItem->price);
		
		echo json_encode($result);
		
	}
	
	static function getCartHtml()
	{
		$cart_list = self::getCartItems();
		$count = $cart_list ? count($cart_list) : 0;
		$summ = 0;
		$html = '';
		if ($cart_list) foreach ($cart_list as $item){
			$summ += $item->price;
			$name = mb_strlen($item->name) > 15 ? mb_substr($item->name, 0, 15).'...' : $item->name;
			$html .= '<li class="cart-item"><span class="cart-title">'.$name.'</span><span class="badge badge-warning cart-price">'.$item->price.' р.</span></li>';
		}
		return $html;
	}
	
	static function getCartItems()
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.cart');
		if ($data)
		{
			$cart = unserialize($data);
			if (!empty($cart))
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				
				$query->select('*');
				$query->from('#__catalogue_items');
				$query->where('id IN ('.implode(',', $cart).')');
			
				$db->setQuery($query);
				$items = $db->loadObjectList();	
			}
			return $items;
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
				$query->from('#__catalogue_items');
				
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
	
	static function inCart($id)
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_catalogue.cart');
		if ($data)
		{
			$cart = unserialize($data);
			return in_array($id, $cart);
		}
		return false;
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
				'method' => THUMBNAIL_METHOD_HEIGHT_PRIORITY,
			);
		
		$midOptions = array(
				'width' => $width,
				'height' => $height,
				'method' => THUMBNAIL_METHOD_HEIGHT_PRIORITY,
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


