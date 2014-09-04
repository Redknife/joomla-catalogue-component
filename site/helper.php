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


	static function createThumb($id, $image, $width, $height, $suffix = 'min')
	{
		$resized_folder = 'resized';

		// check for the existence of source image
		$abs_source_img = JPATH_BASE.DS.$image;
		if(!file_exists($abs_source_img)){
			return false;
		}

		$images_dir = dirname($image);

		$abs_images_dir = JPATH_BASE.DS.$images_dir;

		$abs_new_img_dir = $abs_images_dir.DS.$resized_folder.DS.$id.DS.$suffix;

		$abs_new_img = $abs_new_img_dir.DS.basename($image);

		if (!file_exists($abs_images_dir.DS.$resized_folder))
		{
			mkdir($abs_images_dir.DS.$resized_folder, 0777);
		}

		if (!file_exists($abs_images_dir.DS.$resized_folder.DS.$id))
		{
			mkdir($abs_images_dir.DS.$resized_folder.DS.$id, 0777);
		}

		if (!file_exists($abs_new_img_dir))
		{
			mkdir($abs_new_img_dir, 0777);
		}

		$sizeOptions = array(
				'width' => $width,
				'height' => $height,
				'method' => THUMBNAIL_METHOD_SCALE_MIN,
			);

		$p = str_replace('.jpg', '-'.$suffix.'.jpg', $abs_new_img_dir.DS.basename($image));

		if (!file_exists($p))
		{
			$thumb = new Thumbnail;
			$resizeImage = $thumb->render($image, $sizeOptions);
			@imageJpeg($resizeImage, $p, 90);
			@imagedestroy($resizeImage);
		}

		return $images_dir.DS.$resized_folder.DS.$id.DS.$suffix.DS.str_replace('.jpg', '-'.$suffix.'.jpg', basename($image));
	}

}


