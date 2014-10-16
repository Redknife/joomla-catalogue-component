<?php

defined('_JEXEC') or die;

class CatalogueControllerAttrs extends JControllerAdmin
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getModel($name = 'Attr', $prefix = 'CatalogueModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function edit()
    {
        $id = JRequest::getVar('id', 0);
        $this->setRedirect('index.php?option=com_catalogue&view=attr&layout=edit&id=' . $id);
    }


}