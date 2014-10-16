<?php

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/catalogue.php';


class JFormFieldManufacturersList extends JFormFieldList
{

    protected static $options = array();
    protected $type = 'ManufacturersList';

    public function getOptions()
    {

        $hash = md5($this->element);

        if (!isset(static::$options[$hash])) {


            static::$options[$hash] = parent::getOptions();

            $options = array();

            $options = CatalogueHelper::getManufacturersOptions();

            static::$options[$hash] = array_merge(static::$options[$hash], $options);


        }

        return static::$options[$hash];


    }
}
