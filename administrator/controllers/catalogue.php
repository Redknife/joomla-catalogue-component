<?php


defined('_JEXEC') or die;

class CatalogueControllerCatalogue extends JControllerAdmin
{

    protected $text_prefix = 'COM_CATALOGUE_CATALOGUE';


    public function __construct($config = array())
    {
        parent::__construct($config);

    }


    public function getModel($name = 'Item', $prefix = 'CatalogueModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }


}
