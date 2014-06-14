<?php

defined('_JEXEC') or die;

define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_COMPONENT.'/helpers/route.php';

$controller = JControllerLegacy::getInstance('Catalogue');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
