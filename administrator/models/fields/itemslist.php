<?php

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/catalogue.php';


class JFormFieldItemsList extends JFormFieldList
{

    protected $type = 'ItemsList';


    public function getOptions()
    {

        return CatalogueHelper::getItemsOptions(true);
    }
}
