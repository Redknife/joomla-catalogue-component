<?php

defined('_JEXEC') or die;

class CatalogueControllerCountries extends JControllerAdmin
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getModel($name = 'Country', $prefix = 'CatalogueModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function edit()
    {
        $id = JRequest::getVar('id', 0);
        $this->setRedirect('index.php?option=com_catalogue&view=country&layout=edit&id=' . $id);
    }


}