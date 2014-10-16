<?php

defined('_JEXEC') or die;

class CatalogueTableCatalogue extends JTable
{

    public function __construct(&$_db)
    {
        parent::__construct('#__catalogue_item', 'id', $_db);
    }

    public function bind($array, $ignore = '')
    {

        if (isset($array['item_description'])) {
            $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
            $tagPos = preg_match($pattern, $array['item_description']);

            if ($tagPos == 0) {
                $this->introtext = $array['item_description'];
                $this->fulltext = '';
            } else {
                list($this->introtext, $this->fulltext) = preg_split($pattern, $array['item_description'], 2);
            }
        }

        if (isset($array['params']) && is_array($array['params'])) {
            $tmp = array();
            foreach ($array['params']['attr'] as $key => $param) {
                $tmp['attr_' . $key] = $param;
            }
            $array['params'] = json_encode($tmp);
        }

        if (isset($array['metadata']) && is_array($array['metadata'])) {
            $registry = new JRegistry;
            $registry->loadArray($array['metadata']);
            $array['metadata'] = (string)$registry;
        }

        if (isset($array['techs']) && is_array($array['techs'])) {
            $registry = new JRegistry;
            $registry->loadArray($array['techs']);
            $array['techs'] = (string)$registry;
        }

        return parent::bind($array, $ignore);
    }

    public function store($updateNulls = false)
    {
        /*
                if (is_array($this->params))
                {
                    $registry = new JRegistry;
                    $registry->loadArray($this->params);
                    $this->params = (string) $registry;
                }
        */
        if (is_array($this->techs)) {
            $registry = new JRegistry;
            $registry->loadArray($this->techs);
            $this->techs = (string)$registry;
        }

        $date = JFactory::getDate();
        $user = JFactory::getUser();

        if ($this->id) {
            // Existing item
            $this->modified = $date->toSql();
            $this->modified_by = $user->get('id');
        } else {
            // New contact. A contact created and created_by field can be set by the user,
            // so we don't touch either of these if they are set.
            if (!(int)$this->created) {
                $this->created = $date->toSql();
            }
            if (empty($this->created_by)) {
                $this->created_by = $user->get('id');
            }
        }


        $result = parent::store($updateNulls);
        $values = array();

        $query_assoc = 'DELETE FROM #__catalogue_assoc WHERE item_id = ' . $this->id;
        $this->_db->setQuery($query_assoc);
        $this->_db->execute();

        $keyName = $this->getKeyName();
        $this->load($this->$keyName);

        $jform = JFactory::getApplication()->input->post->get('jform', array(), 'array');
        if (!isset($jform['assoc']['published'])) {
            $jform['assoc']['published'] = array();
        }
        $assocs = array_map(array($this, '_restructData'), $jform['assoc']['assoc_id'], $jform['assoc']['ordering'], $jform['assoc']['published']);


        if (!empty($assocs)) {
            $query_assoc = 'INSERT INTO #__catalogue_assoc (`item_id`, `assoc_id`, `ordering`, `published`) VALUES ';

            foreach ($assocs as $assoc) {
                $values[] = '(' . $this->id . ',' . $assoc['assoc_id'] . ',' . $assoc['ordering'] . ',' . $assoc['published'] . ')';
            }

            if (is_array($values) && !empty($values)) {
                $query_assoc .= implode(',', $values);
                $this->_db->setQuery($query_assoc);
                $this->_db->execute();
            }
        }

        $values = array();

        $query_image = 'DELETE FROM #__catalogue_attr_image WHERE item_id = ' . $this->id;
        $this->_db->setQuery($query_image);
        $this->_db->execute();

        $attr_images = array_map(array($this, '_restructDataImage'), $jform['params']['attr_id'], $jform['params']['attr_image']);


        if (!empty($attr_images)) {
            $query_attr_images = 'INSERT INTO #__catalogue_attr_image (`item_id`, `attr_id`, `attr_image`) VALUES ';

            foreach ($attr_images as $attr_image) {
                if ($attr_image['attr_image'])
                    $values[] = '(' . $this->id . ',' . $attr_image['attr_id'] . ',\'' . $attr_image['attr_image'] . '\')';
            }

            if (is_array($values) && !empty($values)) {
                $query_attr_images .= implode(',', $values);
                $this->_db->setQuery($query_attr_images);
                $this->_db->execute();
            }

        }

        $values = array();

        $query_attr_price = 'DELETE FROM #__catalogue_attr_price WHERE item_id = ' . $this->id;
        $this->_db->setQuery($query_attr_price);
        $this->_db->execute();

        $attr_prices = array_map(array($this, '_restructDataPrice'), $jform['params']['attr_id'], $jform['params']['attr_price']);


        if (!empty($attr_prices)) {
            $query_attr_prices = 'INSERT INTO #__catalogue_attr_price (`item_id`, `attr_id`, `attr_price`) VALUES ';

            foreach ($attr_prices as $attr_price) {
                if ($attr_price['attr_price'])
                    $values[] = '(' . $this->id . ',' . $attr_price['attr_id'] . ',' . $attr_price['attr_price'] . ')';

            }

            if (is_array($values) && !empty($values)) {
                $query_attr_prices .= implode(',', $values);
                $this->_db->setQuery($query_attr_prices);
                $this->_db->execute();
            }
        }

        // REVIEWS..
        $values = array();

        $query_reviews = 'DELETE FROM #__catalogue_item_review WHERE item_id = ' . $this->id;
        $this->_db->setQuery($query_reviews);
        $this->_db->execute();

        // $keyName = $this->getKeyName();
        // $this->load($this->$keyName);

        // $jform = JFactory::getApplication()->input->post->get('jform', array(), 'array');
        if (!isset($jform['reviews']['published'])) {
            $jform['reviews']['published'] = array();
        }
        if (!isset($jform['reviews']['ordering'])) {
            $jform['reviews']['ordering'] = array();
        }

        $reviews = array_map(array($this, '_restructDataReview'), $jform['reviews']['review_date'], $jform['reviews']['review_fio'], $jform['reviews']['review_rate'], $jform['reviews']['review_text'], $jform['reviews']['ordering'], $jform['reviews']['published']);

        if (!empty($reviews)) {
            $query_reviews = 'INSERT INTO #__catalogue_item_review (`id`, `item_id`, `item_review_date`, `item_review_fio`, `item_review_rate`, `item_review_text`, `ordering`, `published`) VALUES ';

            foreach ($reviews as $review) {
                $values[] = "(''," . $this->id . "," . escapeshellarg($review["review_date"]) . "," . escapeshellarg($review["review_fio"]) . "," . escapeshellarg($review["review_rate"]) . "," . escapeshellarg($review["review_text"]) . "," . $review["review_ordering"] . "," . $review["review_published"] . ")";
            }

            if (is_array($values) && !empty($values)) {
                $query_reviews .= implode(',', $values);
                $this->_db->setQuery($query_reviews);
                $this->_db->execute();
            }
        }
        // ..REVIEWS

        return $result;

    }

    protected function _restructData($assoc_id, $ordering = 0, $published = 0)
    {
        if ($published == NULL) {
            $published = 0;
        }
        if ($assoc_id) {
            return array('assoc_id' => $assoc_id, 'ordering' => $ordering, 'published' => $published);
        }
    }

    protected function _restructDataParams($attr_id, $attr_value = 0)
    {
        return array('attr_id' => (string)$attr_id, 'attr_value' => $attr_value);
    }

    protected function _restructDataImage($attr_id, $attr_image = 0)
    {
        list($null, $id) = explode('_', $attr_id, 2);
        return array('attr_id' => (int)$id, 'attr_image' => (string)$attr_image);
    }

    protected function _restructDataPrice($attr_id, $attr_price = 0)
    {
        list($null, $id) = explode('_', $attr_id, 2);
        return array('attr_id' => (int)$id, 'attr_price' => (string)$attr_price);
    }

    protected function _restructDataReview($review_date = '', $review_fio, $review_rate, $review_text, $review_ordering = 0, $review_published = 0)
    {
        return array('review_date' => (string)$review_date, 'review_fio' => (string)$review_fio, 'review_rate' => (int)$review_rate, 'review_text' => (string)$review_text, 'review_ordering' => (int)$review_ordering, 'review_published' => (int)$review_published);
    }

}