<?php

defined('_JEXEC') or die;

class CatalogueTableAttrDir extends JTable
{
    /**
     * Constructor
     *
     * @since    1.5
     */
    public function __construct(&$_db)
    {
        parent::__construct('#__catalogue_attrdir', 'id', $_db);
    }

    public function store($updateNulls = false)
    {

        $result = parent::store($updateNulls);
        $values = array();

        $query = 'DELETE FROM #__catalogue_attrdir_category WHERE attrdir_id = ' . $this->id;

        $this->_db->setQuery($query);
        $this->_db->execute();

        $keyName = $this->getKeyName();
        $this->load($this->$keyName);

        $jform = JFactory::getApplication()->input->post->get('jform', array(), 'array');

        $cat_ids = $jform['category_id'];

        if (!empty($cat_ids)) {
            $insert_query = 'INSERT INTO #__catalogue_attrdir_category (`attrdir_id`, `category_id`) VALUES ';

            foreach ($cat_ids as $category_id) {
                $values[] = '(' . $this->id . ',' . $category_id . ')';
            }

            if (is_array($values) && !empty($values)) {
                $insert_query .= implode(',', $values);
                $this->_db->setQuery($insert_query);
                $this->_db->execute();
            }
        }

        return $result;
    }

}