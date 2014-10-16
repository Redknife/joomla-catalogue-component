<?php

defined('_JEXEC') or die;

class CatalogueTable extends JTable
{

    public function bind($array, $ignore = '')
    {

        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry;
            $registry->loadArray($array['params']);
            $array['params'] = (string)$registry;
        }

        if (isset($array['metadata']) && is_array($array['metadata'])) {
            $registry = new JRegistry;
            $registry->loadArray($array['metadata']);
            $array['metadata'] = (string)$registry;
        }

        return parent::bind($array, $ignore);
    }


    public function featured($pks = null, $state = 1, $userId = 0)
    {
        // Initialise variables.
        $k = $this->_tbl_key;

        // Sanitize input.
        JArrayHelper::toInteger($pks);
        $userId = (int)$userId;
        $state = (int)$state;

        // If there are no primary keys set check to see if the instance key is set.
        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            } // Nothing to set publishing state on, return false.
            else {
                $e = new JException(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                $this->setError($e);

                return false;
            }
        }

        // Update the publishing state for rows with the given primary keys.
        $query = $this->_db->getQuery(true);
        $query->update($this->_tbl);
        $query->set('featured = ' . (int)$state);

        // Determine if there is checkin support for the table.
        if (property_exists($this, 'checked_out') || property_exists($this, 'checked_out_time')) {
            $query->where('(checked_out = 0 OR checked_out = ' . (int)$userId . ')');
            $checkin = true;
        } else {
            $checkin = false;
        }

        // Build the WHERE clause for the primary keys.
        $query->where($k . ' = ' . implode(' OR ' . $k . ' = ', $pks));

        $this->_db->setQuery($query);

        // Check for a database error.
        if (!$this->_db->query()) {
            $e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_PUBLISH_FAILED', get_class($this), $this->_db->getErrorMsg()));
            $this->setError($e);

            return false;
        }

        // If checkin is supported and all rows were adjusted, check them in.
        if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
            // Checkin the rows.
            foreach ($pks as $pk) {
                $this->checkin($pk);
            }
        }

        // If the JTable instance value is in the list of primary keys that were set, set the instance.
        if (in_array($this->$k, $pks)) {
            $this->featured = $state;
        }

        require_once('frontpage.php');
        $table = JTable::getInstance('Frontpage', 'CatalogueTable');

        if ($table->load(array('path' => $this->path)) && ($this->id == 0)) {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_CATEGORY_UNIQUE_PATH'));
            return false;
        }

        if ($state) {
            if (!$table->path) {
                $query = $this->_db->getQuery(true);
                $query->insert($table->_tbl);
                $query->set('path = ' . $this->_db->quote($this->path));
                $this->_db->setQuery($query);

                // Check for a database error.
                if (!$this->_db->query()) {
                    $e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_PUBLISH_FAILED', get_class($table), $this->_db->getErrorMsg()));
                    $this->setError($e);

                    return false;
                }
            }
        } else {

            if ($table->path) {
                $table->delete();
            }

        }

        $table->reorder();

        $this->setError('');
        return true;
    }


}
