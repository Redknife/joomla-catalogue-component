<?php

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/catalogue.php';


class JFormFieldFormsList extends JFormFieldList
{

    protected $type = 'FormsList';


    public function getOptions()
    {
        return CatalogueHelper::getFormsOptions();
    }
}
