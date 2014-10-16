<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_catalogue
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


class CatalogueFilterHelper
{
    static public function getFilterData($filter)
    {

        $db = JFactory::getDbo();

        switch ($filter->filter_field) {

            case 'attr' : {

                switch ($filter->filter_type) {
                    case 'range' :
                        self::getFilterRange('attr_36');
                        break;
                }

                $db->setQuery(
                    $db->getQuery(true)
                        ->select('*')
                        ->from('#__catalogue_attr')
                        ->where('published = 1 AND state = 1 AND attrdir_id = ' . (int)$filter->id)
                        ->order('ordering ASC')
                );
                $result = $db->loadObjectList();
            }
                break;

            case 'fl_price' : {
                $db->setQuery(
                    $db->getQuery(true)
                        ->select($filter->id . ' as id, MIN(price) as from_val, MAX(price) as to_val')
                        ->from('#__catalogue_item')
                        ->where('published = 1 AND state = 1')
                );
                $result = $db->loadObjectList();
            }
                break;

            case 'fl_manufacturer' : {
                $db->setQuery(
                    $db->getQuery(true)
                        ->select('id, manufacturer_name as attr_name')
                        ->from('#__catalogue_manufacturer')
                        ->where('published = 1 AND state = 1')
                        ->order('ordering')
                );
                $result = $db->loadObjectList();
            }
                break;
        }


        foreach ($result as $row) {
            $row->input_name = $filter->filter_field . '_' . $row->id;
        }

        return $result;
    }

    public static function getFilterRange($attr_name)
    {
        $sphinx = new SphinxClient();
        $sphinx->SetServer("localhost", 3312);
        $sphinx->setRankingMode(SPH_RANK_NONE);
        //$sphinx->SetSortMode(SPH_SORT_EXTENDED, 'params.attr_36 DESC');
        $sphinx->setSortMode(SPH_SORT_ATTR_DESC, 'INTEGER(params.attr_36)');
        $sphinx->SetMatchMode(SPH_MATCH_EXTENDED);
        $sphinx->SetSelect('INTEGER(params.attr_36)');
        $sphinx->setLimits(0, 10, 1000);
        $result = $sphinx->Query('', 'searchIndex');
    }
}
