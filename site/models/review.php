<?php

defined('_JEXEC') or die;

class CatalogueModelReview extends JModelAdmin
{


    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_catalogue.review', 'review', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function save($data)
    {
        // Initialise variables;
        $dispatcher = JDispatcher::getInstance();
        $table = $this->getTable();
        $key = $table->getKeyName();
        $pk = (!empty($data[$key])) ? $data[$key] : (int)$this->getState($this->getName() . '.id');
        $isNew = true;

        // Include the content plugins for the on save events.
        JPluginHelper::importPlugin('content');

        // Allow an exception to be thrown.
        try {
            // Load the row if saving an existing record.
            if ($pk > 0) {
                $table->load($pk);
                $isNew = false;
            }

            // Bind the data.
            if (!$table->bind($data)) {
                $this->setError($table->getError());
                return false;
            }

            // Prepare the row for saving
            $this->prepareTable($table);

            // Check the data.
            if (!$table->check()) {
                $this->setError($table->getError());
                return false;
            }

            // Trigger the onContentBeforeSave event.
            $result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, &$table, $isNew));
            if (in_array(false, $result, true)) {
                $this->setError($table->getError());
                return false;
            }

            // Store the data.
            if (!$table->store()) {
                $this->setError($table->getError());
                return false;
            }

            // Clean the cache.
            $this->cleanCache();

            // Trigger the onContentAfterSave event.
            $dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, &$table, $isNew));
        } catch (Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        $pkName = $table->getKeyName();

        if (isset($table->$pkName)) {
            $this->setState($this->getName() . '.id', $table->$pkName);
        }
        $this->setState($this->getName() . '.new', $isNew);

        return $table->id;
    }

    public function getTable($type = 'Reviews', $prefix = 'CatalogueTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

}
