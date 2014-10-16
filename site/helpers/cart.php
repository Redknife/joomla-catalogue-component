<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_catalogue
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


abstract class CatalogueHelperCart
{
    static function getCartItems()
    {
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_catalogue.cart');
        // $app->setUserState('com_catalogue.cart', array());
        // die;
        if ($data) {
            $cart_items = unserialize($data);
            if (!empty($cart_items)) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);

                $query->select('*');
                $query->from('#__catalogue_item');
                $query->where('id IN (' . implode(',', array_keys($cart_items)) . ')');
                $query->order('item_name');
                $db->setQuery($query);
                $items = $db->loadObjectList();

                foreach ($items as $item) {
                    $item->cart_info = $cart_items[$item->id];
                }
                return $items;
            }
        }
        return false;
    }

    static function item_form($n)
    {
        $forms = array('товар', 'товара', 'товаров');
        return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]);
    }

    static function inCart($id)
    {
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_catalogue.cart');
        if ($data) {
            $cart_items = unserialize($data);
            return array_key_exists($id, $cart_items);
        } else {
            return false;
        }
    }

}