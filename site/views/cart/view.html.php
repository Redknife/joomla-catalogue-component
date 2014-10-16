<?php

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT . DS . 'helper.php');

class CatalogueViewCart extends JViewLegacy
{
    protected $items;

    protected $state;


    public function display($tpl = null)
    {

        parent::display($tpl);
    }

}
