<?php

defined('_JEXEC') or die;

abstract class CatalogueModelAdmin extends JModelAdmin
{

    public function featured($pks, $value = 1)
    {
        $dispatcher = JDispatcher::getInstance();
        $user = JFactory::getUser();
        $table = $this->getTable();
        $pks = (array)$pks;

        // Include the content plugins for the change of state event.
        JPluginHelper::importPlugin('content');

        // Access checks.
        foreach ($pks as $i => $pk) {
            $table->reset();

            if ($table->load($pk)) {
                if (!$this->canEditState($table)) {
                    // Prune items that you can't change.
                    unset($pks[$i]);
                    JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
                    return false;
                }
            }
        }

        // Attempt to change the state of the records.
        if (!$table->featured($pks, $value, $user->get('id'))) {
            $this->setError($table->getError());
            return false;
        }

        $context = $this->option . '.' . $this->name;

        // Trigger the onContentChangeState event.
        $result = $dispatcher->trigger($this->event_change_state, array($context, $pks, $value));

        if (in_array(false, $result, true)) {
            $this->setError($table->getError());
            return false;
        }

        // Clear the component's cache
        $this->cleanCache();

        return true;

    }

}


