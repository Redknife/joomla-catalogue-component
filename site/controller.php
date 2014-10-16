<?php


defined('_JEXEC') or die;

require_once(dirname(__FILE__) . DS . 'helper.php');

class CatalogueController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = array())
    {
//        $app = JFactory::getApplication('site');
//        $sphinx_ids = $app->getUserState('com_catalogue.category.filter.sphinx_ids', array());
//
//        if (empty($sphinx_ids)) {
//            $jinput = JFactory::getApplication()->input;
//
//            $sphinx = new SphinxClient();
//            $sphinx->SetServer("localhost", 3312);
//            $sphinx->SetSortMode(SPH_SORT_RELEVANCE);
//            $sphinx->SetMatchMode(SPH_MATCH_EXTENDED);
//
//            $sphinx->SetSelect("id");
//
//            $sphinx->SetLimits(0, 1000, 1000);
//
//            $result = $sphinx->Query('', 'searchIndex');
//
//            if ($result === false) {
//                echo 'Query failed: ' . $sphinx->GetLastError();
//            }
//
//
//            if (isset($result['matches']) && !empty($result['matches'])) {
//                $ids = array();
//
//                foreach ($result['matches'] as $match) {
//                    $ids[] = $match['attrs']['id'];
//
//                }
//
//                if (!empty($ids)) {
//                    $app->setUserState('com_catalogue.category.filter.sphinx_ids', $ids);
//                }
//            }
//        }


        parent::display($cachable, $urlparams);
    }

//    public function search()
//    {
//
//        $app = JFactory::getApplication('site');
//
//        $jinput = JFactory::getApplication()->input;
//        $jform = $jinput->post->get('jform', array(), 'array');
//
//        $manufacturer_filter = array();
//        $price_range = '';
//
//        $sphinx = new SphinxClient();
//        $sphinx->SetServer("localhost", 3312);
//        $sphinx->SetSortMode(SPH_SORT_RELEVANCE);
//        $sphinx->SetMatchMode(SPH_MATCH_EXTENDED);
//        $sphinx->SetLimits(0, 1000, 1000);
//
//        foreach ($jform['filter'] as $key => $value) {
//            $key_arr = explode('_', $key);
//            $count = count($key_arr);
//
//            if ($count > 2) {
//                switch ($key_arr[1]) {
//                    case 'manufacturer':
//                        $manufacturer_filter[] = $key_arr[2];
//                        break;
//                    case 'price':
//                        $price_range = $value;
//                        break;
//                    case 'listbox' :
//                        if ($value) $sphinx->setFilter('params.' . $value, array("1"));
//                        break;
//                }
//            } else {
//                // like where params.attr_N = 1 AND params.attr_M = 1 ...
//                $sphinx->setFilter('params.' . $key, array($value));
//            }
//        }
//
//
//        $sphinx->SetSelect("id");
//        // like where manufacturer_id IN (array)
//        $sphinx->setFilter('manufacturer_id', $manufacturer_filter);
//
//        if ($price_range && strpos($price_range, ';') !== false) {
//            list($min, $max) = explode(';', $price_range);
//            $sphinx->setFilterRange('price', $min, $max);
//        }
//
//
//        $result = $sphinx->Query('', 'searchIndex');
//
//        if ($result === false) {
//            echo 'Query failed: ' . $sphinx->GetLastError();
//        }
//
//
//        if (isset($result['matches']) && !empty($result['matches'])) {
//            $ids = array();
//
//            foreach ($result['matches'] as $match) {
//                $ids[] = $match['attrs']['id'];
//            }
//
//            if (!empty($ids)) {
//                $app->setUserState('com_catalogue.category.filter.sphinx_ids', $ids);
//            }
//        }
//
//
//        $result = array('result' => array('total' => $result['total_found']));
//
//        echo json_encode($result);
//
//        $app->close();
//    }

    public function addToFavorite()
    {
        $app = JFactory::getApplication();

        $favorite = $app->getUserState('com_catalogue.favorite');

        $order = JRequest::getVar('id', 0, 'get', 'int');

        if (!CatalogueHelper::isFavorite($order)) {
            $data = unserialize($favorite);
            if (!is_array($data)) {
                $data = array();
            }

            array_push($data, $order);
            $data = array_unique($data);
            $order = serialize($data);
            $app->setUserState('com_catalogue.favorite', $order);

            echo '1';
        }

        return false;
    }

    public function setFilter()
    {
        // $app = JFactory::getApplication();
        // $cur_page = $_SERVER['HTTP_REFERER'];
        // $app->redirect($cur_page);
        // header("Location: ".$cur_page);
        parent::display();
    }
}
