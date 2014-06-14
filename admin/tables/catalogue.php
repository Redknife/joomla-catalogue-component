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
            $tagPos    = preg_match($pattern, $array['item_description']);

            if ($tagPos == 0) {
                $this->introtext    = $array['item_description'];
                $this->fulltext         = '';
            } else {
                list($this->introtext, $this->fulltext) = preg_split($pattern, $array['item_description'], 2);
            }
        }

        if (isset($array['params']) && is_array($array['params']))
        {
            $registry = new JRegistry;
            $registry->loadArray($array['params']);
            $array['params'] = (string) $registry;
        }

        if (isset($array['metadata']) && is_array($array['metadata']))
        {
            $registry = new JRegistry;
            $registry->loadArray($array['metadata']);
            $array['metadata'] = (string) $registry;
        }

        return parent::bind($array, $ignore);
    }

    public function store($updateNulls = false)
    {

        if (is_array($this->params))
        {
            $registry = new JRegistry;
            $registry->loadArray($this->params);
            $this->params = (string) $registry;
        }

        $date	= JFactory::getDate();
        $user	= JFactory::getUser();

        if ($this->id)
        {
            // Existing item
            $this->modified		= $date->toSql();
            $this->modified_by	= $user->get('id');
        }
        else
        {
            // New contact. A contact created and created_by field can be set by the user,
            // so we don't touch either of these if they are set.
            if (!(int) $this->created)
            {
                $this->created = $date->toSql();
            }
            if (empty($this->created_by))
            {
                $this->created_by = $user->get('id');
            }
        }

        return parent::store($updateNulls);
    }

}